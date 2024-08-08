<?php

declare(strict_types=1);

namespace Tests;

use Tests\Support\TestCase;
use Flame\Exceptions\ManifestException;

class FrontendTest extends TestCase
{
    public function testRender(): void
    {
        $frontend = service("frontend");
        $out = $frontend->render("src/main.tsx");
        $this->assertEquals("<link rel=\"stylesheet\" href=\"http://example.com/assets/main-DiwrgTda.css\" />\n<script src=\"http://example.com/assets/main-4AOhvpUY.js\"></script>", $out);
    }

    public function testPreload(): void
    {
        $frontend = service("frontend");
        $out = $frontend->preload("src/main.tsx");
        $this->assertEquals("<link rel=\"preload\" href=\"http://example.com/assets/main-4AOhvpUY.js\" as=\"script\" />\n<link rel=\"preload\" href=\"http://example.com/assets/main-DiwrgTda.css\" as=\"style\" />", $out);
    }

    public function testRenderException(): void
    {
        $this->expectException(ManifestException::class);
        $this->expectExceptionMessageMatches("/Flame.assetNotFound/");

        $frontend = service("frontend");
        $frontend->render("src/unknown.tsx");
    }

    public function testPreloadException(): void
    {
        $this->expectException(ManifestException::class);
        $this->expectExceptionMessageMatches("/Flame.assetNotFound/");

        $frontend = service("frontend");
        $frontend->preload("src/unknown.tsx");
    }
}
