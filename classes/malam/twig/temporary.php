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
        return Arr::get($this->temporary, $name);
    }

    public function set($name, $value = NULL)
    {
        if (! is_array($name))
        {
            $name = array($name  => $value);
        }

        foreach ($name as $k => $v)
        {
            $this->__set($k, $v);
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
