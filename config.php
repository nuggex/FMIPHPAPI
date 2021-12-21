<?php


define('ROOT_DIR', dirname(__FILE__));
const CONFIG_DIR = ROOT_DIR . '/config';
const CONFIG_FILE = CONFIG_DIR . '/weather.ini';
const CLASSES_DIR = ROOT_DIR . '/classes/';

function Weather_Autoload($className)
{
    $className = str_replace('\\', DIRECTORY_SEPARATOR, $className);
    $file = CLASSES_DIR . $className . '.php';
    if (is_file($file)) {
        require_once($file);
    }
}

spl_autoload_register('Weather_Autoload');