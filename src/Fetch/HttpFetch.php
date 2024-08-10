<?php

declare(strict_types=1);

namespace Flame\Fetch;

use Flame\Config\Flame as FlameConfig;
use Flame\Exceptions\ManifestException;

class HttpFetch implements FetchInterface
{
    public function fetch(FlameConfig $config): string
    {
        $manifestFile = $config->baseUrl . $config->manifestFile;
        if (! preg_match("/\Ahttps?::.*\Z/u", $manifestFile)) {
            throw ManifestException::forInvalidManifestURL($manifestFile);
        }

        $handle = curl_init();
        curl_setopt_array($handle, [
            CURLOPT_URL            => $manifestFile,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HEADER         => false,
            CURLOPT_MAXREDIRS      => 5, // max follows 5 redirects
        ]);

        $manifest = curl_exec($handle);
        if ($manifest === false) {
            throw ManifestException::forFailedHttpRequest();
        }

        // Check status code due to curl_exec returns string even http status code is not 200
        $status = curl_getinfo($handle, CURLINFO_RESPONSE_CODE);
        if ($status !== 200) {
            throw ManifestException::forHttpStautsCode($status);
        }
        curl_close($handle);

        return $manifest;
    }
}
