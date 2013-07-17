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
        $this->set($name, $value);
    }

    public function __get($name)
    {
        return $this->get($name);
    }

    public function get($name, $default = NULL)
    {
        return Arr::get($this->temporary, $name, $default = NULL);
    }

    public function set($name, $value = NULL)
    {
        if (! is_array($name))
        {
            $name = array($name  => $value);
        }

        foreach ($name as $k => $v)
        {
            $this->temporary[$k] = $v;
        }
    }

    public function delete($key)
    {
        if (isset($this->$key))
        {
            unset($this->temporary[$key]);
        }
    }

    public function __isset($key)
    {
        return isset($this->temporary[$key]);
    }

    public function __unset($key)
    {
        $this->delete($key);
    }

    public function as_array()
    {
        return $this->temporary;
    }
}
