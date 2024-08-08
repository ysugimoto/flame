<?php

declare(strict_types=1);

namespace Flame\Exceptions;

use CodeIgniter\Exceptions\CriticalError;
use CodeIgniter\Exceptions\DebugTraceableTrait;

/**
 * ManifestException class represents Manifest related  exceptions.
 * This Exception class holds some message like:
 *
 * - Flame.manifestNotFound  - raises when manifest file could not load.
 * - Flame.malformedManifest - raises when manifest file is invalid JSON format.
 * - Flame.assetNotFound     - raises when asset file is not found in the manifest file.
 *
 * @namespace Flame\Exceptions
 * @class ManifestException
 */
class ManifestException extends CriticalError
{
    use DebugTraceableTrait;

    /**
     * Raise exception when manifest file not found
     *
     * @access public
     * @static
     * @return ManifestException
     */
    public static function forManifestNotFound(): ManifestException
    {
        return new static("Flame.manifestNotFound");
    }

    /**
     * Raise exception when manifest file is invalid
     *
     * @access public
     * @static
     * @return ManifestException
     */
    public static function forMalformedManifest(): ManifestException
    {
        return new static("Flame.malformedManifest");
    }

    /**
     * Raise exception when asset file is not found in the manifest file
     *
     * @access public
     * @static
     * @return ManifestException
     */
    public static function forAssetNotFound(string $file): ManifestException
    {
        return new static("Flame.assetNotFound:$file");
    }
}
