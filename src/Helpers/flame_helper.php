<?php

declare(strict_types=1);

use Flame\Frontend;

if (! function_exists("flame")) {
    /**
     * Shortcut found for render the entry HTML tags
     *
     * @function flame
     * @param string|array $files
     * @return string
     */
    function flame($files)
    {
        return service("frontend", true)->render($files);
    }
}

if (! function_exists("flame_preload")) {
    /**
     * Shortcut found for render the preload HTML tags
     *
     * @function flame_preload
     * @param string|array $files
     * @return string
     */
    function flame_preload($files)
    {
        return service("frontend", true)->preload($files);
    }
}
