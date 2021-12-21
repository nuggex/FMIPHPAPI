<?php


namespace WeatherAPI;

interface Source
{

    public function getLatestWeather($location);

    //public function insertWeatherData();

    //public function clearWeatherData();

    //public function getAllWeatherData();

    function insertObservations($data);

    function insertForecast($data);

    public function getForecastLocations();

    public function getObservationLocations();

    public function getForecastForLocation($location);


}