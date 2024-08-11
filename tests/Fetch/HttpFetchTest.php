<?php

declare(strict_types=1);

namespace Tests;

use Tests\Support\TestCase;
use Flame\Fetch\HttpFetch;

class HttpFetchTest extends TestCase
{
    public function testGetContentType(): void
    {
        $handle = curl_init();
        curl_setopt_array($handle, [
            CURLOPT_URL            => "https://example.com/",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HEADER         => true,
            CURLOPT_MAXREDIRS      => 5, // max follows 5 redirects
        ]);

        $buffer     = curl_exec($handle);
        $headerSize = curl_getinfo($handle, CURLINFO_HEADER_SIZE);
        $header     = substr($buffer, 0, $headerSize);

        $http = new HttpFetch();
        $this->assertStringStartsWith("text/html", $http->getContentType($header));
    }
}
