<?php

declare(strict_types=1);

namespace Flame;

use Flame\Config\Flame as FlameConfig;
use Flame\Exceptions\ManifestException;
use Flame\Asset\Manifest;
use Flame\Asset\Assets;

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
     * Holds the configuration class.
     *
     * @access protected
     * @property Flame\Config\Flame $config
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
        $tags = [];

        foreach ($files as $file) {
            $assets = $this->loadAssets($manifest, $file);
            $tags = array_merge($tags, $assets->generateEntryTags());
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
        // Coerce string array
        if (! is_array($files)) {
            $files = [$files];
        }

        $manifest = $this->loadManifest();
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
            // Load manifest file
            $manifestFile = $this->config->publicPath . DIRECTORY_SEPARATOR . $this->config->manifestFile;
            if (! is_file($manifestFile)) {
                throw ManifestException::forManifestNotFound();
            }

            $manifest = json_decode(file_get_contents($manifestFile), true);
            if ($manifest === false) {
                throw ManifestException::forMalformedManifest();
            }
            $this->manifest = new Manifest($manifest);
        }

        return $this->manifest;
    }
}
