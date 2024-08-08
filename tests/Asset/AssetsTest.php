<?php

declare(strict_types=1);

namespace Tests\Asset;

use Flame\Asset\Assets;
use Flame\Exceptions\ManifestException;
use Tests\Support\TestCase;

class AssetsTest extends TestCase
{
    public function testGeneratePreloadTags(): void
    {
        $assets = new Assets();
        $assets->preload("assets/main.1.css");
        $assets->preload("assets/main.1.js");
        $assets->preload("assets/main.1.js"); // duplicated, should be removed

        $tags = $assets->generatePreloadTags();
        $this->assertEquals([
          'assets/main.1.css' => '<link rel="preload" href="http://example.com/assets/main.1.css" as="style" />',
          'assets/main.1.js'  => '<link rel="preload" href="http://example.com/assets/main.1.js" as="script" />',
        ], $tags);
        $this->assertEquals([], $assets->generateEntryTags());
    }

    public function testGenerateEntryTags(): void
    {
        $assets = new Assets();
        $assets->add("assets/main.1.css");
        $assets->add("assets/main.1.js");
        $assets->add("assets/main.1.js"); // duplicated, should be removed

        $tags = $assets->generateEntryTags();
        $this->assertEquals([
          'assets/main.1.css' => '<link rel="stylesheet" href="http://example.com/assets/main.1.css" />',
          'assets/main.1.js'  => '<script src="http://example.com/assets/main.1.js"></script>',
        ], $tags);
        $this->assertEquals([], $assets->generatePreloadTags());
    }
}
