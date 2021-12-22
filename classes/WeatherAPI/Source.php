<?php


namespace WeatherAPI;

interface Source
{

    public function getLatestWeather($location);

    public function getAllForecastData();

    public function getAllObservationData();

    function insertObservations($data);

    function insertForecast($data);

    public function getForecastLocations();

    public function getObservationLocations();

    public function getForecastForLocation($location);

    public function getObservationsForLocation($location);


}