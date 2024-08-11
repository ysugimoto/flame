<?php

declare(strict_types=1);

namespace Flame\Fetch;

use Flame\Config\Flame as FlameConfig;
use Flame\Exceptions\ManifestException;

/**
 * LocalFetch fetches manifest from local filesystem.
 *
 * @namespace Flame\Fetch
 * @class @LocalFetch
 */
class LocalFetch implements FetchInterface
{
    /**
     * FetchInterface method implementation.
     * Find and read manifest file from specified path.
     *
     * @access public
     * @param FlameConfig $config
     * @return string
     * @throws ManifestException
     */
    public function fetch(FlameConfig $config): string
    {
        $manifestFile = $config->publicPath . $config->manifestFile;
        if (! is_file($manifestFile)) {
            throw ManifestException::forManifestNotFound();
        }

        return file_get_contents($manifestFile);
    }
}
