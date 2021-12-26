# FMIPHPAPI
PHP API For Finnish Meteorological Institute Open Data

XML Interpreter for FMI Open Data written in PHP. 
Can be expanded on other FMI Endpoints and made more dynamic. 

## Requirements (Dev environment)

* PHP >7.3 
* MariaDB >10


## Setup
* Create a database according to docs/weatherdata.sql
* Change weather.ini to match your database configuration
* Create logfile weather.log in /log
* Change $desiredlocation to your desiredlocation
* run "php FetchFMIWeather.php" to check this actually works
* Add a CronJob "php FetchFMIWeather.php" to fetch new weather every hour.


### Check that everything works (Absolutely no guarantees at this point)

* run   php GetWeather.php "location=*Your Set Location*"


## Endpoints

* webroot/api.php?endpointName&param
* All endpoints returns data in json encoded format unless otherwise specified

### savedForecastLocations
Returns all in database present forecast locations

### savedObservationLocations
Returns all in database prsesent observation locations

### getAllObservations
Returns ALL saved observations 

### getAllForecasts
Returns ALL saved forecasts

### currentWeather 
* &location=Arabianranta
* Location must be single word (City,district)
Response includes weather station location as name i.e. "Helsinki Kaisaniemi". Geographical location is not included.

Returns latest reported weather from FMI.

### foreCastForLocation
* &location=Arabianranta
* Location must be single word (City,district)
Returns latest forecast from FMI for input location

### forecastForLocationFromDatabase
* &location=Arabianranta
* Location must be single word (City, district) check saved location with savedForecastLocations
Returns all Forecast data for location from database

### observationsForLocationFromDatabase
* $location=Helsinki Kumpula
* Location name may vary check saved locations with savedObservationLocations

Returns all observations for location from database

## NOTE
Locations in Observartions and Forecasts are NOT the same unless you make them so. 

Forecasts are dynamically calculated locations while observations are weather station locations.

* Observation location format: City District
* Forecast location format: District


This can cause some headache when crossmatching.


Documentation in progress. 
