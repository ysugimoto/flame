<?php

declare(strict_types=1);

namespace Flame\Fetch;

use Flame\Config\Flame as FlameConfig;

abstract class BaseFetch
{
    protected FlameConfig $config;

    public function __construct(FlameConfig $config)
    {
        $this->config = $config;
    }
}
