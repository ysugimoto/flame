<?php

declare(strict_types=1);

namespace Flame\Asset;

/**
 * Assets class manages assets that need to preload/load css and js files.
 *
 * @namespace Flame\Asset
 * @class Assets
 */
class Assets
{
    /**
     * Holds preload files
     *
     * @access protected
     * @property array $preloads
     */
    protected array $preloads = [];

    /**
     * Holds entry css files
     *
     * @access protected
     * @property array $stylesheets
     */
    protected array $stylesheets = [];

    /**
     * Holds entry javascript files
     *
     * @access protected
     * @property array $scripts
     */
    protected array $scripts = [];

    /**
     * Store the CodeIgniter's URI service instance
     *
     * @access protected
     * @property CodeIgniter\Http\SiteURI $uri
     */
    protected $uri;

    /**
     * Constructor.
     *
     * @access public
     */
    public function __construct()
    {
        $this->uri = service("request")->getUri();
    }

    /**
     * Add file to preload target.
     *
     * @access public
     * @param string $file
     */
    public function preload(string $file)
    {
        $this->preloads[] = $file;
    }

    /**
     * Add file to entry target.
     *
     * @access public
     * @param string $file
     */
    public function add(string $file)
    {
        switch (pathinfo($file, PATHINFO_EXTENSION)) {
            case "css":
                $this->stylesheets[] = $file;
                break;
            case "js":
                $this->scripts[] = $file;
                break;
        }
    }

    /**
     * Generate preload link tags.
     *
     * @access public
     * @return array
     */
    public function generatePreloadTags(): array
    {
        $tags = [];

        foreach (array_unique($this->preloads) as $preload) {
            switch (pathinfo($preload, PATHINFO_EXTENSION)) {
                case "css":
                    $tags[$preload] = $this->generatePreload($preload, "style");
                    break;
                case "js":
                    $tags[$preload] = $this->generatePreload($preload, "script");
                    break;
            }
        }

        return $tags;
    }

    /**
     * Generate entry tags.
     *
     * @access public
     * @return array
     */
    public function generateEntryTags(): array
    {
        $tags = [];

        foreach ($this->stylesheets as $stylesheet) {
            $tags[$stylesheet] = $this->generateLink($stylesheet);
        }
        foreach ($this->scripts as $script) {
            $tags[$script] = $this->generateScript($script);
        }

        return $tags;
    }

    /**
     * Generate preload tag.
     *
     * @access protected
     * @param string $file
     * @param string $kind
     * @return string
     */
    protected function generatePreload(string $file, string $kind): string
    {
        $attributes = [
          'rel="preload"',
          'href="' . $this->uri->baseUrl($file) . '"',
          'as="' . $kind . '"',
        ];
        return "<link " . implode(" ", $attributes) . " />";
    }

    /**
     * Generate link tag.
     *
     * @access protected
     * @param string $file
     * @return string
     */
    protected function generateLink(string $file): string
    {
        $attributes = [
          'rel="stylesheet"',
          'href="' . $this->uri->baseUrl($file) . '"',
        ];
        return "<link " . implode(" ", $attributes) . " />";
    }

    /**
     * Generate script tag.
     *
     * @access protected
     * @param string $file
     * @return string
     */
    protected function generateScript(string $file): string
    {
        $attributes = [
          'src="' . $this->uri->baseUrl($file) . '"',
        ];
        return "<script " . implode(" ", $attributes) . "></script>";
    }
}
