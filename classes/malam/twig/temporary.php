<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * @author  arie
 */

class Malam_Twig_Temporary
{
    private $temporary = array();

    public function __set($name, $value)
    {
        $this->temporary[$name] = $value;
    }

    public function __get($name)
    {
        return (isset($this->temporary[$name]) ? $this->temporary[$name] : NULL);
    }

    public function set($name, $value = NULL)
    {
        if (! is_array($name))
            $name = array($name  => $value);

        foreach ($name as $k => $v)
        {
            $this->__set($k, $v);
        }
    }

    public function as_array()
    {
        return $this->temporary;
    }
}