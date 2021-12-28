# CHANGELOG

### Update 2021-12-28

This update which is a merge of the API_Key_Development branch includes a lot of new features and improvements.

I will try to list all feature additions below. 

* Added API Key access restriction using Ramsey UUID
* Added composer.json with RamseyUUID and ext-curl 
* Added Passthrough access bypassing for reading directly from FMI without APIKEY
* Added Tables ObservationLocations, forecastLocations and Users
* Rewrite of FetchFMIWeather.php to allow automation of fetching weather for locations
* Added GetWeatherStations.php which gets all available active weatherstations. NOTE Not all stations supply weatherdata in the format this project currently supports.
* Fixed parse bugs in WeatherAPI.
* Fixed Database keys being over 707 bytes
* Added function to look for weather by FSMISID instead of name
* Added this Changelog File
* Updated weatherdata.sql dump
* Started on a register page to register apikeys (only manual entries)
* Updated weather.ini_template to suit apikey and passthrough.
* Improved query structures in PDOSource
* Added multiple querys to PDOSource

There is still a lot to do an so little time. Will try to implement use tracking, a overview page and other generally important stuff this week.

Please submit issues through github 