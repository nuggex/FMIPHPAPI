<?php
/*
 * File to register your api key
 */


use WeatherAPI\Logger;
use WeatherAPI\PDOSource;
use WeatherAPI\WeatherAPI;


$config = parse_ini_file(CONFIG_DIR . '/weather.ini');


if ($config['devmode']) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

$weather = new WeatherAPI(new PDOSource($config));
$logger = new Logger(ROOT_DIR . $config['logfile']);


$user = $_SERVER['MELLON_uid'];


