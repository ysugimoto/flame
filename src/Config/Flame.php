<?php

declare(strict_types=1);

namespace Flame\Config;

use CodeIgniter\Config\BaseConfig;
use Flame\Enums\FetchMode;

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
     * Specify Fetch mode that how we should find manifest file.
     * See Enums/FetchMode.php
     *
     * @access public
     * @property FetchMode $mode
     */
    public FetchMode $mode = FetchMode::LOCAL;

    /**
     * Enables manifest cache lifetime, once fetch manifest stores to WRITEPATH . flame.manifest.json.
     * Using cache improves performance, especially fetching manifest via HTTP, but you should take care of updating manifest file.
     * If the maniest file updates asynchronously but cache is live, the frontened might be broken.
     * The lifetime is second order.
     *
     * @access public
     * @property int $cacheLifetime;
     */
    public int $cacheLifetime = 0;

    /**
     * Configuration for the HTTP fetch/load base url.
     *
     * Note that this property is used when the fetch mode is "HTTP".
     *
     * @access public
     * @property string $baseUrl
     */
    public string $baseUrl = "https://example.com/";

    /**
     * Configuration for the public directory path.
     * Typically the CodeIgniter's public path is /public
     * but you can change arbitrary path - for example, you don't want to place .flame file in public directory.
     * But then you need to change the manifest generation configuration on flame-vite-plugin configuration.
     *
     * Note that this property is used when the fetch mode is "LOCAL".
     *
     * @access public
     * @property string $publicPath
     */
    public string $publicPath = ROOTPATH . "public/.flame";

    /**
     * Configuration for the flame manifest file that created by flame-vite-plugin.
     * Typically manifest file will be generated into /public/.flame
     *
     * @access public
     * @property string $manifestFile
     */
    public string $manifestFile = ".flame";

    /**
     * Path definition to save cache filem, the file must be writable.
     *
     * @access public
     * @property string $cachePath
     */
    public string $cachePath = WRITEPATH . "flame.manifest.json";

}
