<?php

declare(strict_types=1);

namespace Tests\Asset;

use Flame\Asset\Manifest;
use Flame\Asset\Chunk;
use Flame\Exceptions\ManifestException;
use Tests\Support\TestCase;

class ManifestTest extends TestCase
{
    public function testGetChunk(): void
    {
        $json = json_decode(file_get_contents(__DIR__ . "/../../public/.vite/manifest.json"), true);
        $manifest = new Manifest($json);

        $this->assertInstanceOf(Chunk::class, $manifest->chunk("src/main.tsx"));

        $this->expectException(ManifestException::class);
        $this->expectExceptionMessageMatches("/Flame.assetNotFound/");

        $manifest->chunk("unknown");
    }
}
