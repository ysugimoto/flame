<?php

declare(strict_types=1);

namespace Tests\Asset;

use Flame\Asset\Chunk;
use Tests\Support\TestCase;

class ChunkTest extends TestCase
{
    public function testChunk(): void
    {
        $json = json_decode(file_get_contents(__DIR__ . "/../../public/.vite/manifest.json"), true);
        $item = $json["src/main.tsx"];
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
        $json = json_decode(file_get_contents(__DIR__ . "/../../public/.vite/manifest.json"), true);
        $item = $json["src/main.css"];
        $chunk = new Chunk($item);

        $this->assertEquals($item["file"], $chunk->file);
        $this->assertEquals("", $chunk->name);
        $this->assertEquals($item["src"], $chunk->src);
        $this->assertEquals([], $chunk->css);
        $this->assertEquals([], $chunk->imports);
        $this->assertEquals(true, $chunk->isEntry);
    }
}
