<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * @author  arie
 */

return array(
    'theme_name'    => 'default',
    'extensions'    => array(),
    'templates'     => APPPATH.'views',
    'suffix'        => 'html',
    'environment'   => array(
        'debug'                 => FALSE,
        'trim_blocks'           => FALSE,
        'charset'               => 'utf-8',
        'base_template_class'   => 'Twig_Template',
        'cache'                 => APPPATH.'cache/twig',
        'auto_reload'           => TRUE,
        'strict_variables'      => FALSE,
        'autoescape'            => FALSE,
        'optimizations'         => -1,
    ),
);