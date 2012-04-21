<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * @author  arie
 */

// Load the Twig class autoloader
require Kohana::find_file('vendor', 'Twig/lib/Twig/Autoloader');

// Register the Twig class autoloader
Twig_Autoloader::register();
