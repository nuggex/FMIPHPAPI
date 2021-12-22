<?php


namespace WeatherAPI;

class WeatherAPI
{

    var $source;
    var $message;
    var $error;

    function __construct($source)
    {
        $this->source = $source;
    }

    public function getLatestWeather($location)
    {
        return $this->source->getLatestWeather($location);
    }

    public function getObservationsForLocation($location)
    {
        return $this->source->getObservationsForLocation($location);
    }

    public function getForecastLocations()
    {
        return $this->source->getForecastLocations();
    }

    public function getObservationLocations()
    {
        return $this->source->getObservationLocations();
    }

    public function getForecastForLocation($location)
    {
        return $this->source->getForecastForLocation($location);
    }

    public function getAllForecastData()
    {
       return $this->source->getAllForecastData();
    }

    public function getAllObservationData()
    {
        return $this->source->GetAllObservationData();
    }

    private function insertObservations($data)
    {
        return $this->source->insertObservations($data);
    }

    private function insertForecast($data)
    {
        return $this->source->insertForecast($data);
    }


    public function insertForecastData($data): int
    {
        $rowcount = 0;
        foreach ($data as $wd) {
            $rowcount += $this->insertForecast($wd);
        }

        return $rowcount;
    }

    public function insertObservationData($data): int
    {
        $rowcount = 0;
        foreach ($data as $wd) {
            $rowcount += $this->insertObservations($wd);
        }
        return $rowcount;
    }


    /*
     * Parse the FMI XML with simplexml and xpath to create a multi level array which we can insert into our database.
     * If the result that is input here doesn't include the fields mentioned errors will be had and bad things will probably happen.
     * TRY - Catch should be implemented.
     */


    public function parseWeatherData($data): array
    {

        $xml = simplexml_load_string($data);
        $gmlDoubleOrNil = (string)$xml->xpath("//gml:doubleOrNilReasonTupleList")[0];
        $labels = $xml->xpath("//swe:DataRecord/swe:field");
        //$timestamp = (string)$xml->xpath("//wfs:FeatureCollection")[0]['timeStamp'];
        $location = (string)$xml->xpath("//gml:name")[0];

        $tst = explode("\n", $xml->xpath("//gmlcov:positions")[0]);
        $timestamps = [];
        foreach ($tst as $pos) {
            $timestamps[] = array_values(array_filter(explode(" ", $pos)));
        }
        $timestamps = array_values(array_filter($timestamps));

        $labelsText = [];
        foreach ($labels as $label) {
            $labelsText[] = (string)$label['name'][0];
        }
        $labelsText = array_values(array_filter($labelsText));

        $gmlArray = explode("\n", $gmlDoubleOrNil);
        $gmlFinal = [];
        foreach ($gmlArray as $gml) {
            if (strlen($gml) > 0) {
                $gmlFinal[] = array_values(array_filter(explode(" ", $gml)));
            }
        }

        $gmlFinal = array_values(array_filter($gmlFinal));
        $weatherData = [];
        foreach ($gmlFinal as $wdkey => $wd) {
            $weatherData[$wdkey]["timeStamp"] = $timestamps[$wdkey][2];
            $weatherData[$wdkey]['location'] = $location;
            foreach ($labelsText as $labelkey => $value) {
                $weatherData[$wdkey][$value] = $wd[$labelkey];
            }
        }
        return $weatherData;
    }


    /*
     * Fetch forecast for input location. Default is Arabianranta
    * Check FMI for list of avaiblable locations.
    * If a location is not input the location will be closest possible location
    */


    public function fetchForeCast($location = "Arabianranta,Helsinki")
    {
        $url = "http://opendata.fmi.fi/wfs?service=WFS&version=2.0.0&request=getFeature&storedquery_id=fmi::forecast::hirlam::surface::point::multipointcoverage&place=";

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url . $location);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);

        return $output;
    }


    /*
     * Fetch observational data for location
     * If the location isn't found FMI tries to match location to a nearby place. If
     * Badly formed place names will return an error from FMI.
     */

    public function fetchWeather($location = "Arabianranta,Helsinki")
    {
        $url = "http://opendata.fmi.fi/wfs?service=WFS&version=2.0.0&request=getFeature&storedquery_id=fmi::observations::weather::hourly::multipointcoverage&place=";

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url . $location);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);

        return $output;
    }

    public function getAllForecastDataForLocation($location)
    {
        $weatherData = $this->getForecastForLocation($location);
        $weatherData = $this->unixTimeToDateFormat($weatherData);
        return json_encode($weatherData);
    }

    public function getAllObservationsForLocation($location)
    {
        $weatherData = $this->getObservationsForLocation($location);
        $weatherData = $this->unixTimeToDateFormat($weatherData);
        return json_encode($weatherData);
    }

    public function getCurrentWeatherForLocation($location)
    {

        $weatherData = $this->parseWeatherData($this->fetchWeather($location));


        // Make weather Data more readable and jsonify

        return $this->makeObservationReadable(end($weatherData));

    }

    public function getForecastForCurrentLocation($location)
    {

        $weatherData = $this->parseWeatherData($this->fetchForeCast($location));
        $weatherData = $this->unixTimeToDateFormat($weatherData);
        return json_encode($weatherData);
    }

    private function unixTimeToDateFormat($weatherData): array
    {

        foreach ($weatherData as $key => $entry) {
            foreach ($entry as $wdkey => $wd) {
                if ($wdkey == "timeStamp") {
                    $weatherData[$key][$wdkey] = date("Y-m-d\TH:i:s", $wd);
                }
            }
        }
        return $weatherData;
    }


    public function test_input($data): string
    {
        $data = trim($data);
        $data = stripslashes($data);
        return htmlspecialchars($data);
    }


    /*
     * Translates one observation object to a more human readable json format
     * $observation is ONE observation object
     */
    private function makeObservationReadable($observation)
    {
        $latestWeather = [];
        foreach ($observation as $key => $wd) {

            switch ($key) {
                case "timeStamp":
                    $latestWeather['timeStamp'] = date("Y-m-d\TH:i:s", $wd);
                    break;
                case "location":
                    $latestWeather['location'] = $wd;
                    break;
                case "TA_PT1H_AVG":
                    $latestWeather['Temperature_1H_Average'] = $wd;
                    break;
                case "TA_PT1H_MAX":
                    $latestWeather['Temperature_1H_Max'] = $wd;
                    break;
                case "TA_PT1H_MIN":
                    $latestWeather['Temperature_1H_Min'] = $wd;
                    break;
                case "RH_PT1H_AVG":
                    $latestWeather['RelativeHumidity_1H_Average'] = $wd;
                    break;
                case "WS_PT1H_AVG":
                    $latestWeather['WindSpeed_1H_Average'] = $wd;
                    break;
                case "WS_PT1H_MAX":
                    $latestWeather['WindSpeed_1H_Max'] = $wd;
                    break;
                case "WS_PT1H_MIN":
                    $latestWeather['WindSpeed_1H_Min'] = $wd;
                    break;
                case "WD_PT1H_AVG":
                    $latestWeather['WindDirection_1H_Min'] = $wd;
                    break;
                case "PRA_PT1H_ACC":
                    $latestWeather['Precipitation_1H_Amount'] = $wd;
                    break;
                case "PRI_PT1H_MAX":
                    $latestWeather['Precipitation_Intensity_Max'] = $wd;
                    break;
                case "PA_PT1H_AVG":
                    $latestWeather['Air_Pressure_1H_Average'] = $wd;
                    break;
                case "WAWA_PT1H_RANK":
                    $latestWeather['Present_Weather_Auto'] = $wd;
                    break;
            }
        }
        return json_encode($latestWeather);
    }
}