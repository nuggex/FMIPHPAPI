# FMIPHPAPI

PHP API For Finnish Meteorological Institute Open Data

XML Interpreter for FMI Open Data written in PHP. Can be expanded on other FMI Endpoints and made more dynamic.

## See CHANGELOG.MD for recent changes.

## This is a work in progress and features will be added as the project progresses

## Requirements (Dev environment)

* PHP >7.3
* MariaDB >10
* Ramsey UUID >4.2

## Setup

* Create a database according to docs/weatherdata.sql
* Change weather.ini to match your database configuration
* In weather.ini you can enable APIKEY only access
* In weather.ini you can enable passthrough read access for polling FMI Directly without DB writes access
* Create logfile weather.log in /log
* Change $desiredlocation to your desiredlocation
* run "php FetchFMIWeather.php" to check this actually works
* Add a CronJob "php FetchFMIWeather.php" to fetch new weather every hour.

### Check that everything works (Absolutely no guarantees at this point)

* run php GetWeather.php "location=*Your Set Location*"

## Automation

To get the automated weather fetching going you need to setup a couple things You need to setup your environment as stated above and then run
GetWeatherStations.php in the console. After this you will have to set the "enabled" value to 1 for every weatherstation you wan't to use directly in the
Database.

For foreCast locations you only need to add rows to the database with your desired forecast locations, as before these have to be single worded location names.
i.e. "Kontula".

After this all that is left is to add a cronjob for FetchFMIWeather and watch the data flow in.

## Endpoints

* webroot/api.php?endpointName&param
* All endpoints return data in json encoded format unless otherwise specified

### savedForecastLocations

Returns all in database present forecast locations

### savedObservationLocations

Returns all in database present observation locations

### getAllObservations

Returns ALL saved observations

### getAllForecasts

Returns ALL saved forecasts

### currentWeather

* &location=Arabianranta
* Location must be single word (City,district)
  Response includes weather station location as name i.e. "Helsinki Kaisaniemi". Geographical location is not included.

Returns the latest reported weather from FMI.

### foreCastForLocation

* &location=Arabianranta
* Location must be single word (City,district)
  Returns the latest forecast from FMI for input location

### forecastForLocationFromDatabase

* &location=Arabianranta
* Location must be single word (City, district) check saved location with savedForecastLocations Returns all Forecast data for location from database

### observationsForLocationFromDatabase

* $location=Helsinki Kumpula
* Location name may vary check saved locations with savedObservationLocations

Returns all observations for location from database

## NOTE

Locations in Observations and Forecasts are NOT the same unless you make them so.

Forecasts are dynamically calculated locations while observations are weather station locations.

* Observation location format: City District
* Forecast location format: District

This can cause some headache when cross matching.

Documentation in progress. 
