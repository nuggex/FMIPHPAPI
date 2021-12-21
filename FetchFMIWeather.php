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

$desiredLocation = "Kumpula,Helsinki";


/*
 * Get Forcast Data, parse the data and insert it
 *
 *
 */
$weatherForecastData = $weather->parseWeatherData($weather->fetchForeCast($desiredLocation));
$forecastInserResult = $weather->insertForecastData($weatherForecastData);

$logger->msg("Inserted / Updated " . $forecastInserResult . " Forecasts\n");

$weatherObservations = $weather->parseWeatherData($weather->fetchWeather($desiredLocation));
$observationInsertResult = $weather->insertObservationData($weatherObservations);
$logger->msg("Inserted / Updated " . $observationInsertResult . " Observations\n");





