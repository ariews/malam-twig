<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * @author  arie
 */

abstract class Malam_Twig_Meta
{
    public static $theme = Malam_Twig::THEME;

    private static $_available_methods = array(
        'image',
        'script',
        'style',
        'flash'
    );

    public static function __callStatic($name, $arguments)
    {
        $name   = strtolower($name);
        $folder = $name;

        if (! in_array($name, self::$_available_methods))
            return;

        $pattern = 'themes/:theme_name/media/:folder/:filename';
        $config  = Kohana::$config->load('twig');
        $name    = strtolower($name);
        $backend = FALSE;
        $link_only = FALSE;

        $arguments += array(NULL, NULL, FALSE);

        list($filenames, $attributes, $link_only) = $arguments;

        if (NULL === $filenames || empty($filenames))
            return;

        if (is_string($attributes))
            $attributes = array($attributes);

        if ($name == 'flash' || TRUE === (bool) $link_only)
        {
            if (is_array($filenames))
                return;

            return URL::site(__($pattern, array(
                ':theme_name'   => Malam_Meta::$theme,
                ':folder'       => $folder,
                ':filename'     => $filenames
            )));
        }

        else
        {
            if (! is_array($filenames))
                $filenames = array($filenames);

            $string = '';

            if (! is_array($attributes))
                $attributes = array($attributes);

            foreach ($filenames as $filename)
            {
                $filepath = Valid::url($filename) ? $filename : __($pattern, array(
                    ':theme_name'   => Malam_Meta::$theme,
                    ':folder'       => $folder,
                    ':filename'     => $filename
                ));

                $string .= HTML::$name($filepath, $attributes);
            }

            return $string;
        }
    }
}