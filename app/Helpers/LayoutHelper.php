<?php

namespace App\Helpers;

class LayoutHelper
{
    /**
     * Get the current layout preference for a specific view
     *
     * @param string $view The view name to get the layout for
     * @param string $default The default layout if none is set
     * @return string
     */
    public static function getLayoutPreference(string $view, string $default = 'table'): string
    {
        return session()->get("layout_preferences.{$view}", $default);
    }

    /**
     * Set the layout preference for a specific view
     *
     * @param string $view The view name to set the layout for
     * @param string $layout The layout preference to set
     * @return void
     */
    public static function setLayoutPreference(string $view, string $layout): void
    {
        session()->put("layout_preferences.{$view}", $layout);
    }
} 