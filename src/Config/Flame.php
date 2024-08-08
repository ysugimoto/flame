<?php

declare(strict_types=1);

namespace Flame\Config;

use CodeIgniter\Config\BaseConfig;

/*
 * Flame configuration class.
 * Change any properties if you want to change public directory or manifest file place.
 *
 * @namespace Flame\Config
 * @class Flame
 */
class Flame extends BaseConfig
{
    /**
     * Configuration for the public directory path.
     * Typically the CodeIgniter's public path is defined as PUBLICPATH consant
     * but you can change arbitrary path if you want.
     *
     * @access public
     * @property string $publicPath
     */
    public string $publicPath = PUBLICPATH;

    /**
     * Configuration for the vite manifest JSON file.
     * Typically manifest file will be generated into PUBLICPATH/.vite/manifest.json
     * but you can change arbitrary path if you want.
     *
     * @access public
     * @property string $manifestFile
     */
    public string $manifestFile = ".vite/manifest.json";
}
