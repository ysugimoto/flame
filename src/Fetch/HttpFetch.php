<?php

declare(strict_types=1);

namespace Flame\Fetch;

use Flame\Config\Flame as FlameConfig;
use Flame\Exceptions\ManifestException;

/**
 * HttpFetch fetches manifest from HTTP resource.
 *
 * @namespace Flame\Fetch
 * @class @HttpFetch
 */
class HttpFetch implements FetchInterface
{
    /**
     * FetchInterface method implementation.
     * Send HTTP request to specified URL and get response JSON text.
     *
     * @access public
     * @param FlameConfig $config
     * @return string
     * @throws ManifestException
     */
    public function fetch(FlameConfig $config): string
    {
        $manifestFile = rtrim($config->baseUrl, "/"). DIRECTORY_SEPARATOR . $config->manifestFile;
        // File URL must start with http(s):// protocol
        if (! preg_match("/\Ahttps?:\/\/.*\Z/u", $manifestFile)) {
            throw ManifestException::forInvalidManifestURL($manifestFile);
        }

        $handle = curl_init();
        curl_setopt_array($handle, [
            CURLOPT_URL            => $manifestFile,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HEADER         => true,
            CURLOPT_MAXREDIRS      => 5, // max follows 5 redirects
        ]);

        $buffer = curl_exec($handle);
        if ($buffer === false) {
            throw ManifestException::forFailedHttpRequest();
        }

        // Check status code due to curl_exec returns string even http status code is not 200
        $status = curl_getinfo($handle, CURLINFO_RESPONSE_CODE);
        if ($status !== 200) {
            throw ManifestException::forHttpStautsCode($status);
        }

        // Validate response header. Content-Type header must be application/json
        $headerSize = curl_getinfo($handle, CURLINFO_HEADER_SIZE);
        $header     = substr($buffer, 0, $headerSize);
        $body       = substr($buffer, $headerSize);

        $contentType = $this->getContentType($header);
        if (!str_starts_with($contentType, "application/json")) {
            throw ManifestException::forHttpContentType($contentType);
        }
        curl_close($handle);

        return $body;
    }

    /**
     * getContentType returns Content-Type header value from header strings.
     *
     * @access public
     * @param string $header
     * @return string
     */
    public function getContentType(string $header): string
    {
        $lines = preg_split("/(\\r)?\\n/", $header);
        foreach ($lines as $line) {
            if (strpos($line, ":") === false) {
                continue;
            }
            [$key, $value] = explode(":", $line);
            if (strtolower($key) === "content-type") {
                return strtolower(trim($value));
            }
        }
        return "";
    }
}
