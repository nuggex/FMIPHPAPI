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


// Enable posting from CLI


$config = parse_ini_file(CONFIG_DIR . '/weather.ini');


if ($config['devmode']) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}


$weather = new WeatherAPI(new PDOSource($config));
$logger = new Logger(ROOT_DIR . $config['logfile']);

if (!isset($_SERVER["HTTP_HOST"])) {
    parse_str($argv[1], $_GET);
    parse_str($argv[1], $_POST);
}

$authed = true;
$passthrough = (bool)$config['passthrough'];

if ($config['apikey']) {
    $apikey = $_POST['apikey'] ?? "";
    if (!$weather->checkApiKey($apikey)) {
        $authed = false;
    }
}


/*
 * Create an instance of WeatherAPI and Logger
 *
 */

if ($authed || $passthrough) {
    if ($authed) {
        if (isset($_POST['savedForecastLocations'])) {
            $locations = $weather->getForecastLocations();
            echo json_encode($locations);
        }

        if (isset($_POST['savedObservationLocations'])) {
            $locations = $weather->getObservationLocations();
            echo json_encode($locations);
        }

        if (isset($_POST['getAllObservations'])) {
            $weatherData = $weather->getAllObservationData();
            echo json_encode($weatherData);
        }

        if (isset($_POST['getAllForecasts'])) {
            $weatherData = $weather->getAllForecastData();
            echo json_encode($weatherData);
        }

        if (isset($_POST['forecastForLocationFromDatabase'])) {
            $location = $weather->test_input($_POST['location']);
            $weatherForecast = $weather->getAllForecastDataForLocation($location);
            echo $weatherForecast;
        }

        if (isset($_POST['observationsForLocationFromDatabase'])) {
            $location = $weather->test_input($_POST['location']);
            $weatherObservations = $weather->getAllObservationsForLocation($location);
            echo $weatherObservations;
        }
    }
    if (isset($_POST['currentWeather'])) {
        $location = $weather->test_input($_POST['location']);
        $weatherCurrent = $weather->getCurrentWeatherForLocation($location);
        echo $weatherCurrent;
    }


    if (isset($_POST['foreCastForLocation'])) {
        $location = $weather->test_input($_POST['location']);
        $weatherForecast = $weather->getForecastForCurrentLocation($location);
        echo $weatherForecast;
    }
} else {
    $response = "You are not authed to use this service";
    echo json_encode($response);
}