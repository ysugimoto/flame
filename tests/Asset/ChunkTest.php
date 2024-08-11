<?php

declare(strict_types=1);

namespace Tests\Asset;

use Flame\Asset\Chunk;
use Tests\Support\TestCase;
use Tests\Fixture\Manifest as ManifestFixture;

class ChunkTest extends TestCase
{
    public function testChunk(): void
    {
        $manifest = $this->getManifest();
        $item = $manifest["src/main.tsx"];
        $chunk = new Chunk($item);

        $this->assertEquals($item["file"], $chunk->file);
        $this->assertEquals($item["name"], $chunk->name);
        $this->assertEquals($item["src"], $chunk->src);
        $this->assertEquals($item["css"], $chunk->css);
        $this->assertEquals([], $chunk->imports);
        $this->assertEquals(true, $chunk->isEntry);
    }

    public function testReferencedChunk(): void
    {
        $manifest = $this->getManifest();
        $item = $manifest["src/main.css"];
        $chunk = new Chunk($item);

        $this->assertEquals($item["file"], $chunk->file);
        $this->assertEquals("", $chunk->name);
        $this->assertEquals($item["src"], $chunk->src);
        $this->assertEquals([], $chunk->css);
        $this->assertEquals([], $chunk->imports);
        $this->assertEquals(true, $chunk->isEntry);
    }

    private function getManifest(): array
    {
        $json = json_decode(ManifestFixture::getFixture(), true);
        return $json["manifest"];
    }
}
