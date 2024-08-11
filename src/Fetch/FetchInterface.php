<?php

namespace Flame\Fetch;

use Flame\Config\Flame as FlameConfig;

/**
 * Fetch Interface represents fetch manifest buffer from each source.
 */
interface FetchInterface
{
    /**
     * Class has to implement fetch method that provided with configuation
     *
     * @access public
     * @param FlameConfig $config
     * @return string
     */
    public function fetch(FlameConfig $config): string;
}
