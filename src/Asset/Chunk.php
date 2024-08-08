<?php

declare(strict_types=1);

namespace Flame\Asset;

/**
 * Chunk class holds manifest chunk and return as a property
 *
 * @namespace Flame\Asset
 * @class Chunk
 */
class Chunk
{
    /**
     * Chunk field of "file"
     *
     * @access public
     * @property string $file
     */
    public string $file;

    /**
     * Chunk field of "name"
     *
     * @access public
     * @property string $name
     */
    public string $name;

    /**
     * Chunk field of "src"
     *
     * @access public
     * @property string $src
     */
    public string $src;

    /**
     * Chunk field of "css"
     *
     * @access public
     * @property array $css
     */
    public array $css = [];

    /**
     * Chunk field of "imports"
     *
     * @access public
     * @property array $imports
     */
    public array $imports = [];

    /**
     * Chunk field of "isEntry"
     *
     * @access public
     * @property bool $isEntry
     */
    public bool $isEntry = false;

    /**
     * Constructor.
     *
     * @access public
     * @param array $chunk
     */
    public function __construct(array $chunk)
    {
        $this->file    = $chunk["file"];
        $this->src     = $chunk["src"];
        $this->name    = $chunk["name"] ?? "";
        $this->css     = $chunk["css"] ?? [];
        $this->imports = $chunk["imports"] ?? [];
        $this->isEntry = $chunk["isEntry"] ?? false;
    }
}
