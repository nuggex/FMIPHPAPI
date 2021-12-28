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


/**
 * Get all enabled weatherstations
 */
$enabledStations = $weather->getEnabledWeatherStations();

/**
 * Loop through enabled weatherstations
 */
foreach ($enabledStations as $weatherStation) {

    /**
     * If the enabled station has "sää" as a group get weather for it
     */
    if (strpos($weatherStation['groups'], "sää") !== false) {
        $weatherObservations = $weather->parseWeatherData($weather->fetchWeatherByFMISID($weatherStation['fmisid']));
        $observationInsertResult = $weather->insertObservationData($weatherObservations);
        $logger->msg("Inserted / Updated " . $observationInsertResult . " Observations for: " . $weatherStation['name'] . "\n");
    }
}


/**
 * Get all enabled forecast locations
 *
 * Forecast location names can be any valid district or city in Finland or recorded by FMI
 */

$forecastLocations = $weather->getEnabledForecastLocations();

/*
     * Loop throguh all enabled forecast locations and insert them accordingly
     */
foreach ($forecastLocations as $location) {

    $weatherForecastData = $weather->parseWeatherData($weather->fetchForeCast($location['name']));
    $forecastInserResult = $weather->insertForecastData($weatherForecastData);

    $logger->msg("Inserted / Updated " . $forecastInserResult . " Forecasts\n");

}


/*
 * Get Forecast Data, parse the data and insert it
 * For testing purposes
 *

$weatherForecastData = $weather->parseWeatherData($weather->fetchForeCast($desiredLocation));
$forecastInserResult = $weather->insertForecastData($weatherForecastData);

$logger->msg("Inserted / Updated " . $forecastInserResult . " Forecasts\n");

$weatherObservations = $weather->parseWeatherData($weather->fetchWeather($desiredLocation));
$observationInsertResult = $weather->insertObservationData($weatherObservations);
$logger->msg("Inserted / Updated " . $observationInsertResult . " Observations\n");



*/

