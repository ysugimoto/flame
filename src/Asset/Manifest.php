<?php

declare(strict_types=1);

namespace Flame\Asset;

use Flame\Exceptions\ManifestException;

/**
 * Manifest class holds manifests,
 * get Chunk resource corresponds to source file
 *
 * @namespace Flame\Asset
 * @class Manifest
 */
class Manifest
{
    /**
     * Holds loaded manifest data.
     *
     * @access protected
     * @property array $manifest
     */
    protected array $manifest;

    /**
     * Constructor.
     *
     * @access public
     * @param array $manifest
     */
    public function __construct(array $manifest)
    {
        $this->manifest = $manifest;
    }

    /**
     * Get Chunk instance for provided source filename.
     *
     * @access public
     * @param string $file
     * @return Chunk
     * @throws ManifestException
     */
    public function chunk(string $file): Chunk
    {
        if (! isset($this->manifest[$file])) {
            throw ManifestException::forAssetNotFound($file);
        }

        return new Chunk($this->manifest[$file]);
    }
}
