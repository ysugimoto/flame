<?php

declare(strict_types=1);

namespace Flame\Config;

use CodeIgniter\Config\BaseService;
use Flame\Config\Flame as FlameConfig;
use Flame\Frontend;
use Flame\Enums\FetchMode;
use Flame\Fetch\FetchInterface;
use Flame\Fetch\HttpFetch;
use Flame\Fetch\LocalFetch;

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

    public static function manifest($getShared = true): FetchInterface
    {
        if ($getShared) {
            return self::getSharedInstance("manifest");
        }
        /** @var FlameConfig $config */
        $config = config('Flame');

        return match($config->mode) {
            FetchMode::HTTP => new HttpFetch(),
            default         => new LocalFetch(),
        };
    }
}
