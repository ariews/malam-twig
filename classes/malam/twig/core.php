<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * @author  arie
 */

class Malam_Twig_Core
{
    const THEME = 'default';

    /**
     * @var Twig
     */
    public $twig;

    public static function instance($theme = Malam_Twig::THEME)
    {
        static $instance;
        empty($instance) && $instance = new Malam_Twig($theme);
        return $instance;
    }

    public static function factory($theme = Malam_Twig::THEME)
    {
        return new Malam_Twig($theme);
    }

    public function __construct($theme = Malam_Twig::THEME)
    {
        $this->set_theme($theme);
    }

    public function set_theme($theme = Malam_Twig::THEME)
    {
        $config     = Kohana::$config->load('twig');
        $theme_dir  = "{$config->templates}themes/{$theme}";
        $loader     = new Twig_Loader_Filesystem($theme_dir);
        $twig       = new Twig_Environment($loader, $config->environment);

        $twig->addFunction('K', new Twig_Function_Function('Malam_Twig::staticCall', array(
            'is_safe' => array('html')
        )));

        if ($config->extensions)
        {
            foreach ($config->extensions as $extension)
            {
                $twig->addExtension(new $extension);
            }
        }

        Malam_Meta::$theme = $theme;

        return $this->twig = $twig;
    }

    public function Twig()
    {
        return $this->twig;
    }

    public static function staticCall($function, $args = array())
    {
        $args = func_get_args();
        unset($args[0]);

        if (is_callable($function))
            return call_user_func_array($function, $args);

        return FALSE;
    }
}