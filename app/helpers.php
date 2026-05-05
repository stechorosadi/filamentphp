<?php

if (! function_exists('lroute')) {
    /**
     * Generate a locale-prefixed route URL.
     * Automatically prepends the current app locale to the route parameters.
     */
    function lroute(string $name, array $params = []): string
    {
        return route($name, array_merge(['locale' => app()->getLocale()], $params));
    }
}
