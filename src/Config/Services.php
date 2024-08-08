<?php

declare(strict_types=1);

namespace Flame\Config;

use CodeIgniter\Config\BaseService;
use Flame\Config\Flame as FlameConfig;
use Flame\Frontend;

/*
 * Service management class for this namespace.
 *
 * @namespace Flame\Config
 * @class Services
 */
class Services extends BaseService
{
    /*
     * Returns the factory class of Flame\Frontend.
     *
     * @access public
     * @static
     * @param bool $getShared
     * @rerurn Frontend
     */
    public static function frontend($getShared = true): Frontend
    {
        if ($getShared) {
            return self::getSharedInstance("frontend");
        }

        /** @var FlameConfig $config */
        $config = config('Flame');

        return new Frontend($config);
    }
}
