<?php

namespace WeatherAPI;

class PDOSource implements Source
{

    var $pdo;

    function __construct($config)
    {
        $opt = [];
        $this->pdo = new \PDO($config['db.dsn'], $config['db.username'], $config['db.password'], $opt);
    }


    public function getLatestWeather($location)
    {
        $s = $this->pdo->prepare("SELECT * FROM observations WHERE location=:location ORDER BY timestamp DESC LIMIT 1");
        $s->execute(array(
            ":location" => $location
        ));
        return $s->fetchAll(\PDO::FETCH_ASSOC);
    }


    public function getForecastLocations()
    {
        $s = $this->pdo->prepare("SELECT DISTINCT location FROM forecasts");
        $s->execute();
        return $s->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getObservationLocations()
    {
        $s = $this->pdo->prepare("SELECT DISTINCT location FROM observations");
        $s->execute();
        return $s->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getForecastForLocation($location)
    {
        $s = $this->pdo->prepare("SELECT * FROM forecasts WHERE location=:location AND timestamp > DATE(NOW())");
        $s->execute(array(
            ":location" => $location
        ));
        return $s->fetchAll(\PDO::FETCH_ASSOC);

    }

    /*
     * Warning!
     * Pulling all data will probably take a long time and is probably not what you want to do.
     * Ever.
     */

    public function getAllForcastData()
    {
        $s = $this->pdo->prepare("SELECT * FROM forecasts");
        $s->execute();
        return $s->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getAllObservationData()
    {
        $s = $this->pdo->prepare("SELECT * FROM observations");
        $s->execute();
        return $s->fetchAll(\PDO::FETCH_ASSOC);
    }


    public function insertForecast($data)
    {
        $s = $this->pdo->prepare("INSERT INTO forecasts (tsloc, timestamp, location, GeopHeight, Temperature, Pressure, Humidity, WindDirection, WindSpeedMS, WindUMS, 
                                                        WindVMS, MaximumWind, WindGust, DewPoint, TotalCloudCover, WeatherSymbol3, LowCloudCover, MediumCloudCover, 
                                                        HighCloudCover, Precipitation1h, PrecipitationAmount, RadiationGlobalAccumulation, RadiationLWAccumulation, 
                                                        RadiationNetSurfaceLWAccumulation, RadiationNetSurfaceSWAccumulation, RadiationDiffuseAccumulation, LandSeaMask) 
                                                VALUES (:tsloc, :timeStamp, :location, :GeopHeight, :Temperature, :Pressure, :Humidity, :WindDirection, :WindSpeedMS,
                                                        :WindUMS, :WindVMS, :MaximumWind, :WindGust, :DewPoint, :TotalCloudCover, :WeatherSymbol3,	
                                                        :LowCloudCover, :MediumCloudCover, :HighCloudCover, :Precipitation1h, :PrecipitationAmount,	
                                                        :RadiationGlobalAccumulation, :RadiationLWAccumulation, :RadiationNetSurfaceLWAccumulation,	
                                                        :RadiationNetSurfaceSWAccumulation, :RadiationDiffuseAccumulation, :LandSeaMask) ON DUPLICATE KEY UPDATE 
                                                        GeopHeight=:GeopHeight, Temperature=:Temperature, Pressure=:Pressure,Humidity=:Humidity, WindDirection=:WindDirection,	
                                                        WindSpeedMS=:WindSpeedMS, WindUMS=:WindUMS, WindVMS=:WindVMS, MaximumWind=:MaximumWind, WindGust=:WindGust, 
                                                        DewPoint=:DewPoint, TotalCloudCover=:TotalCloudCover, WeatherSymbol3=:WeatherSymbol3, LowCloudCover=:LowCloudCover, 
                                                        MediumCloudCover=:MediumCloudCover,	HighCloudCover=:HighCloudCover,	Precipitation1h=:Precipitation1h, 
                                                        PrecipitationAmount=:PrecipitationAmount, RadiationGlobalAccumulation=:RadiationGlobalAccumulation,
                                                        RadiationLWAccumulation=:RadiationLWAccumulation, RadiationNetSurfaceLWAccumulation=:RadiationNetSurfaceLWAccumulation,
                                                        RadiationNetSurfaceSWAccumulation=:RadiationNetSurfaceSWAccumulation, RadiationDiffuseAccumulation=:RadiationDiffuseAccumulation, LandSeaMask=:LandSeaMask");
        $s->execute(array(
            ":tsloc" => $data['timeStamp'] . $data['location'],
            ":timeStamp" => date("Y-m-d\TH:i:s", $data['timeStamp']),
            ":location" => $data['location'],
            ":GeopHeight" => $data['GeopHeight'],
            ":Temperature" => $data['Temperature'],
            ":Pressure" => $data['Pressure'],
            ":Humidity" => $data['Humidity'],
            ":WindDirection" => $data['WindDirection'],
            ":WindSpeedMS" => $data['WindSpeedMS'],
            ":WindUMS" => $data['WindUMS'],
            ":WindVMS" => $data['WindVMS'],
            ":MaximumWind" => $data['MaximumWind'],
            ":WindGust" => $data['WindGust'],
            ":DewPoint" => $data['DewPoint'],
            ":TotalCloudCover" => $data['TotalCloudCover'],
            ":WeatherSymbol3" => $data['WeatherSymbol3'],
            ":LowCloudCover" => $data['LowCloudCover'],
            ":MediumCloudCover" => $data['MediumCloudCover'],
            ":HighCloudCover" => $data['HighCloudCover'],
            ":Precipitation1h" => $data['Precipitation1h'],
            ":PrecipitationAmount" => $data['PrecipitationAmount'],
            ":RadiationGlobalAccumulation" => $data['RadiationGlobalAccumulation'],
            ":RadiationLWAccumulation" => $data['RadiationLWAccumulation'],
            ":RadiationNetSurfaceLWAccumulation" => $data['RadiationNetSurfaceLWAccumulation'],
            ":RadiationNetSurfaceSWAccumulation" => $data['RadiationNetSurfaceSWAccumulation'],
            ":RadiationDiffuseAccumulation" => $data['RadiationDiffuseAccumulation'],
            ":LandSeaMask" => $data['LandSeaMask']
        ));
        return $s->rowCount();

    }

    public function insertObservations($data)
    {
        $s = $this->pdo->prepare("INSERT INTO observations 
                                (tsloc, timestamp, location, TA_PT1H_AVG, TA_PT1H_MAX, TA_PT1H_MIN, RH_PT1H_AVG, WS_PT1H_AVG, WS_PT1H_MAX, 
                                 WS_PT1H_MIN, WD_PT1H_AVG, PRA_PT1H_ACC, PRI_PT1H_MAX, PA_PT1H_AVG, WAWA_PT1H_RANK) 
                                VALUES (:tsloc, :timestamp, :location, :TA_PT1H_AVG, :TA_PT1H_MAX, :TA_PT1H_MIN, :RH_PT1H_AVG,:WS_PT1H_AVG, :WS_PT1H_MAX, :WS_PT1H_MIN, 
                                        :WD_PT1H_AVG, :PRA_PT1H_ACC, :PRI_PT1H_MAX, :PA_PT1H_AVG, :WAWA_PT1H_RANK) 
                                ON DUPLICATE KEY UPDATE TA_PT1H_AVG=:TA_PT1H_AVG, TA_PT1H_MAX=:TA_PT1H_MAX, TA_PT1H_MIN=:TA_PT1H_MIN, RH_PT1H_AVG=:RH_PT1H_AVG, 
                                                        WS_PT1H_AVG=:WS_PT1H_AVG, WS_PT1H_MAX=:WS_PT1H_MAX, WS_PT1H_MIN=:WS_PT1H_MIN, WD_PT1H_AVG=:WD_PT1H_AVG,
                                                        PRA_PT1H_ACC=:PRA_PT1H_ACC, PRI_PT1H_MAX=:PRI_PT1H_MAX, PA_PT1H_AVG=:PA_PT1H_AVG, WAWA_PT1H_RANK=:WAWA_PT1H_RANK");
        $s->execute(array(
            ":tsloc" => $data['timeStamp'] . $data['location'],
            ":timestamp" => date("Y-m-d\TH:i:s", $data['timeStamp']),
            ":location" => $data['location'],
            ":TA_PT1H_AVG" => $data['TA_PT1H_AVG'],
            ":TA_PT1H_MAX" => $data['TA_PT1H_MAX'],
            ":TA_PT1H_MIN" => $data['TA_PT1H_MIN'],
            ":RH_PT1H_AVG" => $data['RH_PT1H_AVG'],
            ":WS_PT1H_AVG" => $data['WS_PT1H_AVG'],
            ":WS_PT1H_MAX" => $data['WS_PT1H_MAX'],
            ":WS_PT1H_MIN" => $data['WS_PT1H_MIN'],
            ":WD_PT1H_AVG" => $data['WD_PT1H_AVG'],
            ":PRA_PT1H_ACC" => $data['PRA_PT1H_ACC'],
            ":PRI_PT1H_MAX" => $data['PRI_PT1H_MAX'],
            ":PA_PT1H_AVG" => $data['PA_PT1H_AVG'],
            ":WAWA_PT1H_RANK" => $data['WAWA_PT1H_RANK']
        ));
        return $s->rowCount();
    }

}