<?php

/*
 * This file gets all available observable weatherstations and inserts them into to the database
 * The idea is to have all stations available and enable the ones you want to fetch data for.
 * You can run this file once a week to update the listing.
 * A GUI to enable stations will be created in a later phase.
 */


use WeatherAPI\Logger;
use WeatherAPI\WeatherAPI;
use WeatherAPI\PDOSource;

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

$weatherStations = getWeatherStations();
$counter = 0;
foreach ($weatherStations as $key => $ws) {
    if ($key === "items") {
        foreach ($ws as $item) {
            if (empty($item['ended'])) {
                $weather->insertWeatherStation($item);
                $counter++;
            }
        }
    }
}

echo "Inserted " . $counter . " Weather-stations, (some were dropped due to \o/)";

function getWeatherStations()
{
    $url = "https://cdn.fmi.fi/weather-observations/metadata/all-finnish-observation-stations.fi.json";

    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_Exec($curl);
    curl_close($curl);

    return json_decode($output, true);
}

