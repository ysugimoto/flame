<?php

declare(strict_types=1);

namespace Tests\Support;

use CodeIgniter\Config\Factories;
use CodeIgniter\Test\CIUnitTestCase;
use Flame\Config\Flame;

/**
 * @internal
 */
abstract class TestCase extends CIUnitTestCase
{
    protected function setUp(): void
    {
        $this->resetServices();

        parent::setUp();

        // Load helpers that should be autoloaded
        helper(['flame']);
    }

    protected function wrong()
    {
        // Inject wrong config
        /** @var Flame $config */
        $config = config("Flame");
        $config->publicPath = "wrong";
        Factories::injectMock("config", "Flame", $config);
    }

    protected function tearDown(): void
    {
        Factories::reset();
    }
}
