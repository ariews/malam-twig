<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * @author  arie
 */

class Malam_Twig_Meta
{
    const PATTERN = 'themes/:theme_name/media/:folder/:filename';

    protected static $_available_methods = array(
        'image',
        'script',
        'style',
    );

    protected $_theme;

    protected function _compile($folder, $filenames, array $attributes = NULL, $link_only = FALSE)
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
        $attributes = array('rel' => 'shortcut icon', 'type' => 'image/x-icon');
        return $this->header_link($icon, $attributes);
    }

    public function header_link($href, array $attributes = NULL, $type = 'image')
    {
        if (! in_array($type, self::$_available_methods))
            $type = 'image';

        $href = $this->_compile($type, $href, NULL, TRUE);
        $attributes += array('href' => $href);

        return '<link'.HTML::attributes($attributes).'/>';
    }

    protected function _path_stripper($path)
    {
        return ltrim(preg_replace('#[./]{2,}#i', '/', $path), '/');
    }

    protected function _create_path($folder, $filename)
    {
        return __(Meta::PATTERN, array(
            ':theme_name'       => $this->_theme,
            ':folder'           => $this->_path_stripper($folder),
            ':filename'         => $this->_path_stripper($filename),
        ));
    }

    protected function __construct($theme = Malam_Twig::THEME)
    {
        $this->_theme = $theme;
    }

    public static function instance($theme = Malam_Twig::THEME)
    {
        static $instance;
        empty($instance) && $instance = new Meta($theme);
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

        return $this->_compile($name, $filenames, $attributes, $link_only);
    }
}