<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * @author  arie
 */

class Malam_Twig_Meta
{
    const PATTERN = 'themes/:theme_name/media/:folder/:filename';

    private static $_available_methods = array(
        'image',
        'script',
        'style',
    );

    private $_theme;

    private function __compile($folder, $filenames, array $attributes = NULL, $link_only = FALSE)
    {
        if (NULL === $filenames)
            return;

        if (TRUE === $link_only && ! is_array($filenames))
        {
            return URL::site($this->_create_path($folder, $filenames));
        }
        else
        {
            if (! is_array($filenames))
                $filenames = array($filenames);

            $string = '';

            if (NULL !== $attributes && ! is_array($attributes))
            {
                $attributes = array($attributes);
            }

            foreach ($filenames as $filename)
            {
                $filename = Valid::url($filename)
                        ? $filename
                        : $this->_create_path($folder, $filename);

                $string .= HTML::$folder($filename, $attributes);
            }

            return $string;
        }
    }
    
    public function favicon($icon = 'favicon.ico')
    {
        return "<link rel='shortcut icon' href='".
                $this->__compile('image', $icon, NULL, TRUE)
                ."' type='image/x-icon' />";
    }

    private function _path_stripper($path)
    {
        return ltrim(preg_replace('#[./]{2,}#i', '/', $path), '/');
    }

    private function _create_path($folder, $filename)
    {
        return __(Meta::PATTERN, array(
            ':theme_name'       => $this->_theme,
            ':folder'           => $this->_path_stripper($folder),
            ':filename'         => $this->_path_stripper($filename),
        ));
    }

    private function __construct($theme = Malam_Twig::THEME)
    {
        $this->_theme = $theme;
    }

    public static function instance($theme = Malam_Twig::THEME)
    {
        static $instance;
        empty($instance) && $instance = new self($theme);
        return $instance;
    }

    public static function __callStatic($name, $arguments)
    {
        $arguments += array(NULL, NULL, FALSE);
        list($filenames, $attributes, $link_only) = $arguments;

        return Meta::instance()->$name($filenames, $attributes, $link_only);
    }

    public function __call($name, $arguments)
    {
        $name = strtolower($name);

        $arguments += array(NULL, NULL, FALSE);
        list($filenames, $attributes, $link_only) = $arguments;

        if (! in_array($name, self::$_available_methods) || NULL === $filenames || empty($filenames))
            return;

        return $this->__compile($name, $filenames, $attributes, $link_only);
    }
}