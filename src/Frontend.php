<?php

declare(strict_types=1);

namespace Flame;

use Flame\Config\Flame as FlameConfig;
use Flame\Exceptions\ManifestException;
use Flame\Asset\Manifest;
use Flame\Asset\Assets;
use Flame\Fetch\FetchInterface;
use Flame\Fetch\LocalFetch;
use Flame\Fetch\HttpFetch;
use Flame\Enums\FetchMode;
use Flame\Exceptions\EnvironmentException;

/**
 * Main action class, load and parse vite manifest file,
 * display asset HTML tags like <link> or <script>.
 *
 * @namespace Flame
 * @class Flame\Frontend
 */
class Frontend
{
    /**
     * Holds the fetch interface class.
     *
     * @access protected
     * @property FlameConfig $config
     */
    protected FlameConfig $config;

    /**
     * Cache the manifest file that once loaded and parsed.
     *
     * @access protected
     * @property ?Flame\Asset\Manifest $manifest
     */
    protected ?Manifest $manifest = null;
    /**
     * Cache the analyzed assets by for the asset file.
     *
     * @access protected
     * @property array $assets
     */
    protected array $assets = [];

    /**
     * Constructor
     *
     * @access public
     * @param FlameConfig $config
     */
    public function __construct(FlameConfig $config)
    {
        $this->config = $config;
    }

    /**
     * Render the entry files the is needed for the page.
     *
     * @access public
     * @param string|array $files
     * @return string
     */
    public function render($files = []): string
    {
        // Coerce string array
        if (! is_array($files)) {
            $files = [$files];
        }

        $manifest = $this->loadManifest();
        // If manifest is generated under the vite dev server, generate corresponds to HRM.
        if ($manifest->getIsServer()) {
            return $this->renderForServer($manifest, $files);
        }

        // Otherwise, generate built assets.


        $manifest = $this->loadManifest();
        $tags = [];

        foreach ($files as $file) {
            $assets = $this->loadAssets($manifest, $file);
            $tags = array_merge($tags, $assets->generateEntryTags());
        }

        return implode("\n", $tags);
    }

    /**
     * Render scripts for vite devserver.
     *
     * @access public
     * @return string
     * @throws EnvironmentException
     */
    public function renderForServer(Manifest $manifest, array $files): string
    {
        // Raise exception if devserver enables but CodeIgniter environment is production
        if (env("CI_ENVIRONMENT", "production") === "production") {
            throw new EnvironmentException("Frontend dev manifest cannot accept on the production env.");
        }

        $host = $manifest->getHost();
        $port = $manifest->getPort();
        $tags = [];
        // If dependent library is react, we need to add more tag for refresh
        if ($manifest->getLibrary() === "react") {
            $tags[] = <<<EOS
<script type="module">
import RefreshRuntime from "http://$host:$port/@react-refresh"
RefreshRuntime.injectIntoGlobalHook(window)
window.\$RefreshReg\$ = () => {}
window.\$RefreshSig\$ = () => (type) => type
window.__vite_plugin_react_preamble_installed__ = true
</script>
EOS;
        }
        // Add vite client via devserver
        $tags[] = sprintf(
            "<script type=\"module\" src=\"http://%s:%d/@vite/client\"></script>",
            $host,
            $port,
        );

        // And load provided files.
        // Note that vite devserver will resolve its dependencies
        // so we don't need to resolve imports and css.
        foreach ($files as $file) {
            $src = $manifest->lookupAlias($file);
            $tags[] = sprintf(
                "<script type=\"module\" src=\"http://%s:%d/%s\"></script>",
                $host,
                $port,
                $src,
            );
        }

        return implode("\n", $tags);
    }


    /**
     * Render the preload files as link tag.
     *
     * @access public
     * @param string|array $files
     * @return string
     */
    public function preload($files = []): string
    {
        $manifest = $this->loadManifest();
        if ($manifest->getIsServer()) {
            return "";
        }

        // Coerce string array
        if (! is_array($files)) {
            $files = [$files];
        }

        $tags = [];

        foreach ($files as $file) {
            $assets = $this->loadAssets($manifest, $file);
            $tags = array_merge($tags, $assets->generatePreloadTags());
        }

        return implode("\n", $tags);
    }

    /**
     * Load and make Assets instance for the provided file.
     *
     * @access protected
     * @param Manifest $manifest
     * @param string $file
     * @return Assets
     */
    protected function loadAssets(Manifest $manifest, string $file): Assets
    {
        if (array_key_exists($file, $this->assets)) {
            return $this->assets[$file];
        }

        $assets = new Assets();
        $chunk = $manifest->chunk($file);
        $assets->preload($chunk->file);

        // Analyze imported files for the file
        foreach ($chunk->imports as $import) {
            $nested = $manifest->chunk($import);

            $assets->preload($chunk->file);

            foreach ($nested->css as $css) {
                $assets->preload($css);
                $assets->add($css);
            }
        }

        $assets->add($chunk->file);

        // Analyse depend CSS files for the file
        foreach ($chunk->css as $css) {
            $assets->preload($css);
            $assets->add($css);
        }
        $this->assets[$file] = $assets;

        return $assets;
    }

    /**
     * Load manifest file.
     *
     * @access protected
     * @throws ManifestException
     * @return Manifest
     */
    protected function loadManifest(): Manifest
    {
        if ($this->manifest === null) {
            $buffer = $this->lookupCache();
            if ($buffer === false) {
                $buffer = service("manifest")->fetch($this->config);
            }

            $decoded = json_decode($buffer, true);
            if ($decoded === false) {
                throw ManifestException::forMalformedManifest();
            }
            $this->manifest = new Manifest($decoded);

            // Write cache only when manifest is production
            if (! $this->manifest->getIsServer()) {
                $this->writeCache($buffer);
            }
        }

        return $this->manifest;
    }

    /**
     * Lookup cache manifest file.
     *
     * @access protected
     * @return string|bool
     */
    protected function lookupCache(): string|bool
    {
        // If cache is disalbed or fetch mode is not HTTP, stop to lookup
        if ($this->config->cacheLifetime === 0 || $this->config->mode !== FetchMode::HTTP) {
            return false;
        }

        $cachePath = $this->config->cachePath;
        $lifetime  = $this->config->cacheLifetime;

        // Check file existence
        if (! is_file($cachePath)) {
            return false;
        }

        // Get the cache file mtime
        $mtime = filemtime($cachePath);
        if ($mtime === false) {
            return false;
        }

        // Check expiration
        if ($mtime + $lifetime > time()) {
            return false;
        }

        return file_get_contents($cachePath);
    }

    /**
     * Lookup cache manifest file.
     *
     * @access protected
     * @return void
     */
    protected function writeCache(string $buffer): void
    {
        // If cache is disabled or fetch mode is not HTTP, stop to write
        if ($this->config->cacheLifetime === 0 || $this->config->mode !== FetchMode::HTTP) {
            return;
        }
        if (file_put_contents($this->config->cachePath, $buffer)) {
            // Ensure update file mtime
            touch($this->config->cachePath);
        }
    }
}
