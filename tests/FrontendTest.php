<?php

declare(strict_types=1);

namespace Tests;

use Tests\Support\TestCase;
use Tests\Fixture\Manifest as ManifestFixture;
use Flame\Exceptions\ManifestException;
use Flame\Frontend;
use Flame\Asset\Manifest;
use Flame\Config\Services;
use Mockery;

class FrontendTest extends TestCase
{
    protected $mock;

    public function setUp(): void
    {
        parent::setUp();

        $fixture = json_decode(ManifestFixture::getFixture(), true);
        $this->mock = Mockery::mock(Frontend::class, [config("Flame")])->makePartial();
        $this->mock->shouldAllowMockingProtectedMethods();
        $this->mock->shouldReceive("loadManifest")->andReturn(new Manifest($fixture));

        Services::injectMock("frontend", $this->mock);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }

    public function testRender(): void
    {
        $frontend = service("frontend");
        $out = $frontend->render("src/main.tsx");
        $this->assertEquals("<link rel=\"stylesheet\" href=\"http://example.com/assets/main-DiwrgTda.css\" />\n<script src=\"http://example.com/assets/main-BTkHr7m7.js\"></script>", $out);
    }

    public function testRenderWithAlias(): void
    {
        $frontend = service("frontend");
        $out = $frontend->render("@main");
        $this->assertEquals("<link rel=\"stylesheet\" href=\"http://example.com/assets/main-DiwrgTda.css\" />\n<script src=\"http://example.com/assets/main-BTkHr7m7.js\"></script>", $out);
    }
    public function testPreload(): void
    {
        $frontend = service("frontend");
        $out = $frontend->preload("src/main.tsx");
        $this->assertEquals("<link rel=\"preload\" href=\"http://example.com/assets/main-BTkHr7m7.js\" as=\"script\" />\n<link rel=\"preload\" href=\"http://example.com/assets/main-DiwrgTda.css\" as=\"style\" />", $out);
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
