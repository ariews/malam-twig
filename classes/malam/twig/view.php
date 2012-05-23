<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * @author  arie
 */

class Malam_Twig_View extends View
{
    /**
     * @var Malam_Twig
     */
    public static $twig;

    public function __construct($file = NULL, array $data = NULL)
    {
        parent::__construct($file, $data);
        Malam_View::$twig = $this->set_theme();
    }

    public static function factory($file = NULL, array $data = NULL)
    {
        return new Malam_View($file, $data);
    }

    protected static function capture($kohana_view_filename, array $kohana_view_data)
    {
        $twig = Malam_View::$twig;

        try {
            return $twig->loadTemplate($kohana_view_filename)
                    ->render(array_merge($kohana_view_data, View::$_global_data));
        } catch (Twig_Error_Loader $e)
        {
            throw new HTTP_Exception_500();
        }
    }

    public function set_theme($theme = Malam_Twig::THEME)
    {
        Malam_View::$twig = Malam_Twig::factory($theme)->Twig();
        return $this;
    }

    public function set_filename($file)
    {
        $config = Kohana::$config->load('twig');

        if (isset($config['suffix']))
        {
             $file = "{$file}.{$config['suffix']}";
        }

        $this->_file = $file;

        return $this;
    }

    public function render($file = NULL)
    {
        if ($file !== NULL)
        {
            $this->set_filename($file);
        }

        if (empty($this->_file))
        {
            throw new Kohana_View_Exception('You must set the file to use within your view before rendering');
        }

        // Combine local and global data and capture the output
        return Malam_View::capture($this->_file, $this->_data);
    }
}