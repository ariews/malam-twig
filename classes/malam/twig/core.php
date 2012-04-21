<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * @author  arie
 */

class Malam_Twig_Core
{
    /**
     * @var Twig
     */
    public $twig;

    public static function instance()
    {
        static $instance;
        empty($instance) && $instance = new Malam_Twig();
        return $instance;
    }

    public function __construct()
    {
        $config = Kohana::$config->load('twig');

        $default_theme  = 'default';
        $theme_name     = $config->theme_name;

        /**
         * Add default_theme path to loader
         */
        $loader = new Twig_Loader_Filesystem($config->templates.'/themes/'.$theme_name);
        $loader->addPath($config->templates.'/themes/'.$default_theme);

        $twig   = new Twig_Environment($loader, $config->environment);

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

        $this->twig = $twig;
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