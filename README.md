# FMIPHPAPI
PHP API For Finnish Meteorological Institute Open Data

XML Interpreter for FMI Open Data written in PHP. 
Can be expanded on other FMI Endpoints and made more dynamic. 


## Setup
* Create a database according to docs/weatherdata.sql
* Change weather.ini to match your database configuration
* Create logfile weather.log in /log
* Change $desiredlocation to your desiredlocation
* run "php FetchFMIWeather.php" to check this actually works
* Add a CronJob "php FetchFMIWeather.php" to fetch new weather every hour.


### Check that everything works (Absolutely no guarantees at this point)

* run   php GetWeather.php "location=*Your Set Location*"


## NOTE
Locations in Observartions and Forecasts are NOT the same unless you make them so. 

Forecasts are dynamically calculated locations while observations are weather station locations.

* Observation location format: City District
* Forecast location format: District


This can cause some headache when crossmatching.


Documentation in progress. 