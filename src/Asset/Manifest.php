<?php

declare(strict_types=1);

namespace Flame\Asset;

use Flame\Config\Flame as FlameConfig;
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
     * Holds loaded vite built manigest data.
     *
     * @access protected
     * @property array $manifest
     */
    protected array $manifest;

    /**
     * Holds alias configurations.
     *
     * @access protected
     * @property array $aliases
     */
    protected array $aliases;

    /**
     * Holds vite devserver info.
     *
     * @access protected
     * @property array|null $server
     */
    protected ?array $server;

    /**
     * Constructor.
     *
     * @access public
     * @param array $manifest
     */
    public function __construct(array $manifest)
    {
        $this->manifest = $manifest["manifest"] ?? [];
        $this->aliases  = $manifest["aliases"] ?? [];
        $this->server   = $manifest["server"] ?? null;
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
        // Resolve alias
        $file = $this->lookupAlias($file);

        if (!isset($this->manifest[$file])) {
            throw ManifestException::forAssetNotFound($file);
        }

        return new Chunk($this->manifest[$file]);
    }

    /**
     * Look up actual file from alias name like "@main".
     *
     * @access protected
     * @param string $file
     * @return string
     */
    public function lookupAlias(string $file): string
    {
        if (strpos($file, "@") !== 0) {
            return $file;
        }
        return $this->aliases[$file] ?? $file;
    }

    /**
     * Get devServer port number.
     *
     * @access public
     * @return int
     */
    public function getPort(): ?int
    {
        return $this->server["port"] ?? null;
    }

    /**
     * Get devServer host.
     *
     * @access public
     * @return string
     */
    public function getHost(): string
    {
        return $this->server["host"] ?? "localhost";
    }

    /**
     * Get library name.
     *
     * @access public
     * @return string
     */
    public function getLibrary(): string
    {
        return $this->server["library"] ?? "";
    }

    /**
     * Get server flag.
     *
     * @access public
     * @return bool
     */
    public function getIsServer(): bool
    {
        return $this->server !== null;
    }
}
