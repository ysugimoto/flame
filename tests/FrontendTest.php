<?php

declare(strict_types=1);

namespace Tests;

use Tests\Support\TestCase;
use Flame\Exceptions\ManifestException;
use Flame\Frontend;
use Flame\Config\Flame;

class FrontendTest extends TestCase
{
    public function testRender(): void
    {
        $this->mockManifest();
        $frontend = service("frontend");
        $out = $frontend->render("src/main.tsx");
        $this->assertEquals("<link rel=\"stylesheet\" href=\"http://example.com/assets/main-DiwrgTda.css\" />\n<script src=\"http://example.com/assets/main-BTkHr7m7.js\"></script>", $out);
    }

    public function testRenderWithAlias(): void
    {
        $this->mockManifest();
        $frontend = service("frontend");
        $out = $frontend->render("@main");
        $this->assertEquals("<link rel=\"stylesheet\" href=\"http://example.com/assets/main-DiwrgTda.css\" />\n<script src=\"http://example.com/assets/main-BTkHr7m7.js\"></script>", $out);
    }
    public function testPreload(): void
    {
        $this->mockManifest();
        $frontend = service("frontend");
        $out = $frontend->preload("src/main.tsx");
        $this->assertEquals("<link rel=\"preload\" href=\"http://example.com/assets/main-BTkHr7m7.js\" as=\"script\" />\n<link rel=\"preload\" href=\"http://example.com/assets/main-DiwrgTda.css\" as=\"style\" />", $out);
    }

    public function testRenderException(): void
    {
        $this->mockManifest();
        $this->expectException(ManifestException::class);
        $this->expectExceptionMessageMatches("/Flame.assetNotFound/");

        $frontend = service("frontend");
        $frontend->render("src/unknown.tsx");
    }

    public function testPreloadException(): void
    {
        $this->mockManifest();
        $this->expectException(ManifestException::class);
        $this->expectExceptionMessageMatches("/Flame.assetNotFound/");

        $frontend = service("frontend");
        $frontend->preload("src/unknown.tsx");
    }

    public function testCacheWritten(): void
    {
        $this->mockFetch(2);
        $frontend = service("frontend");
        $frontend->render("@main");

        $this->assertTrue(
            is_file(WRITEPATH . "flame.manifest.json"),
        );

        /** @var Flame $config */
        $config = config("Flame");

        // Create new instance without service cache.
        $frontend = new Frontend($config);
        $frontend->render("@main");

        // ...and wait for expiring cache file
        sleep(2);

        // And fetch again, cache file must be expired
        $frontend = new Frontend($config);
        $frontend->render("@main");
    }
}
