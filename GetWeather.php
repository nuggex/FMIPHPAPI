<?php

use WeatherAPI\Logger;
use WeatherAPI\WeatherAPI;
use WeatherAPI\PDOSource;

/*
 * Load Config
 * Remember to edit weather.ini to suit your environment
 * Use docs/weatherdata.sql to create a database
 */

require 'config.php';

$config = parse_ini_file(CONFIG_FILE);


if ($config['devmode']) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

/*
 * Create an instance of WeatherAPI and Logger
 *
 */

$weather = new WeatherAPI(new PDOSource($config));
$logger = new Logger(ROOT_DIR . $config['logfile']);

if (!isset($_SERVER["HTTP_HOST"])) {
    parse_str($argv[1], $_GET);
    parse_str($argv[1], $_POST);
}


$location = $_POST['location'];
$weatherData = [];

if($location){
    $weatherData = $weather->getLatestWeather($location);
    getTemperatureFromWeatherForLocation($weatherData);
}


function getTemperatureFromWeatherForLocation($weatherData)
{

    foreach ($weatherData as $wd) {
        foreach ($wd as $key => $value) {
            if ($key === "Temperature" || $key === "TA_PT1H_AVG") {
                echo $wd['timestamp'] . " : " . $value . " c \n";
            }
        }
    }
}
