<?php

declare(strict_types=1);

namespace Tests\Asset;

use Tests\Support\TestCase;
use Tests\Fixture\Manifest as ManifestFixture;
use Flame\Asset\Manifest;
use Flame\Asset\Chunk;
use Flame\Exceptions\ManifestException;

class ManifestTest extends TestCase
{
    public function testGetChunkThrowsNotFoundError(): void
    {
        $fixture = json_decode(ManifestFixture::getFixture(), true);
        $manifest = new Manifest($fixture);

        $this->assertInstanceOf(Chunk::class, $manifest->chunk("src/main.tsx"));

        $this->expectException(ManifestException::class);
        $this->expectExceptionMessageMatches("/Flame.assetNotFound/");

        $manifest->chunk("unknown");
    }

    public function testGetChunkWithAlias(): void
    {
        $fixture = json_decode(ManifestFixture::getFixture(), true);
        $manifest = new Manifest($fixture);

        $this->assertInstanceOf(Chunk::class, $manifest->chunk("@main"));
    }
}
