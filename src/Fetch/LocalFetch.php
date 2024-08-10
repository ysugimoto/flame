<?php

declare(strict_types=1);

namespace Flame\Fetch;

use Flame\Config\Flame as FlameConfig;
use Flame\Exceptions\ManifestException;

class LocalFetch implements FetchInterface
{
    public function fetch(FlameConfig $config): string
    {
        $manifestFile = $config->publicPath . $config->manifestFile;
        if (! is_file($manifestFile)) {
            throw ManifestException::forManifestNotFound();
        }

        return file_get_contents($manifestFile);
    }
}
