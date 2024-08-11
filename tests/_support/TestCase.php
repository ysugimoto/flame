<?php

declare(strict_types=1);

namespace Tests\Support;

use CodeIgniter\Config\Factories;
use CodeIgniter\Test\CIUnitTestCase;
use Tests\Fixture\Manifest as ManifestFixture;
use Flame\Config\Flame;
use Flame\Frontend;
use Flame\Asset\Manifest;
use Flame\Config\Services;
use Flame\Enums\FetchMode;
use Flame\Fetch\HttpFetch;
use Mockery;

/**
 * @internal
 */
abstract class TestCase extends CIUnitTestCase
{
    protected $manifestMock;
    protected $fetchMock;

    protected function setUp(): void
    {
        $this->resetServices();

        parent::setUp();

        /** @var Flame $config */
        $config = config("Flame");
        $config->mode = FetchMode::HTTP;
        $config->cacheLifetime = 1; // 1 sec
        Factories::injectMock("config", "Flame", $config);

        if (is_file($config->cachePath)) {
            unlink($config->cachePath);
        }
    }

    protected function tearDown(): void
    {
        Factories::reset();
        Mockery::close();
    }

    protected function mockManifest(): void
    {
        $fixture = json_decode(ManifestFixture::getFixture(), true);
        $this->manifestMock = Mockery::mock(Frontend::class, [config("Flame")])->makePartial();
        $this->manifestMock->shouldAllowMockingProtectedMethods();
        $this->manifestMock->shouldReceive("loadManifest")->andReturn(new Manifest($fixture));

        Services::injectMock("frontend", $this->manifestMock);
    }

    protected function mockFetch(?int $times = 1): void
    {
        $this->fetchMock = Mockery::mock(HttpFetch::class)->makePartial();
        // @phpstan-ignore-next-line
        $this->fetchMock->shouldReceive("fetch")
                        ->times($times)
                        ->with(config("Flame"))
                        ->andReturn(ManifestFixture::getFixture());

        Services::injectMock("manifest", $this->fetchMock);
    }
}
