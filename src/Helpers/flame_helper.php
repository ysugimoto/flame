<?php

declare(strict_types=1);

if (! function_exists("flame")) {
    /**
     * Shortcut function to render the entry HTML tags
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
     * Shortcut function to render the preload HTML tags
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
