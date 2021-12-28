-- --------------------------------------------------------
-- Host:                         hood.arcada.fi
-- Server version:               10.3.31-MariaDB-0+deb10u1 - Debian 10
-- Server OS:                    debian-linux-gnu
-- HeidiSQL Version:             11.3.0.6295
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for weatherdata
CREATE DATABASE IF NOT EXISTS `weatherdata` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `weatherdata`;

-- Dumping structure for table weatherdata.forecastLocations
CREATE TABLE IF NOT EXISTS `forecastLocations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(125) NOT NULL DEFAULT '',
  `enabled` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `location` (`name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

-- Data exporting was unselected.

-- Dumping structure for table weatherdata.forecasts
CREATE TABLE IF NOT EXISTS `forecasts` (
  `tsloc` varchar(125) NOT NULL,
  `timestamp` timestamp NULL DEFAULT NULL,
  `location` varchar(125) DEFAULT NULL,
  `GeopHeight` float DEFAULT NULL,
  `Temperature` float DEFAULT NULL,
  `Pressure` float DEFAULT NULL,
  `Humidity` float DEFAULT NULL,
  `WindDirection` float DEFAULT NULL,
  `WindSpeedMS` float DEFAULT NULL,
  `WindUMS` float DEFAULT NULL,
  `WindVMS` float DEFAULT NULL,
  `MaximumWind` float DEFAULT NULL,
  `WindGust` float DEFAULT NULL,
  `DewPoint` float DEFAULT NULL,
  `TotalCloudCover` float DEFAULT NULL,
  `WeatherSymbol3` float DEFAULT NULL,
  `LowCloudCover` float DEFAULT NULL,
  `MediumCloudCover` float DEFAULT NULL,
  `HighCloudCover` float DEFAULT NULL,
  `Precipitation1h` float DEFAULT NULL,
  `PrecipitationAmount` float DEFAULT NULL,
  `RadiationGlobalAccumulation` float DEFAULT NULL,
  `RadiationLWAccumulation` float DEFAULT NULL,
  `RadiationNetSurfaceLWAccumulation` float DEFAULT NULL,
  `RadiationNetSurfaceSWAccumulation` float DEFAULT NULL,
  `RadiationDiffuseAccumulation` float DEFAULT NULL,
  `LandSeaMask` float DEFAULT NULL,
  `updated` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`tsloc`) USING BTREE,
  KEY `timestamp` (`timestamp`),
  KEY `location` (`location`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Data exporting was unselected.

-- Dumping structure for table weatherdata.observationLocations
CREATE TABLE IF NOT EXISTS `observationLocations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(125) NOT NULL,
  `enabled` int(11) DEFAULT 0,
  `x` float DEFAULT 0,
  `y` float DEFAULT 0,
  `started` int(11) DEFAULT 0,
  `fmisid` int(11) DEFAULT 0,
  `groups` varchar(256) DEFAULT '0',
  `description` varchar(1000) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `location` (`name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2095 DEFAULT CHARSET=utf8mb4;

-- Data exporting was unselected.

-- Dumping structure for table weatherdata.observations
CREATE TABLE IF NOT EXISTS `observations` (
  `tsloc` varchar(125) NOT NULL,
  `timestamp` timestamp NULL DEFAULT NULL,
  `location` varchar(125) DEFAULT NULL,
  `TA_PT1H_AVG` float DEFAULT NULL,
  `TA_PT1H_MAX` float DEFAULT NULL,
  `TA_PT1H_MIN` float DEFAULT NULL,
  `RH_PT1H_AVG` float DEFAULT NULL,
  `WS_PT1H_AVG` float DEFAULT NULL,
  `WS_PT1H_MAX` float DEFAULT NULL,
  `WS_PT1H_MIN` float DEFAULT NULL,
  `WD_PT1H_AVG` float DEFAULT NULL,
  `PRA_PT1H_ACC` float DEFAULT NULL,
  `PRI_PT1H_MAX` float DEFAULT NULL,
  `PA_PT1H_AVG` float DEFAULT NULL,
  `WAWA_PT1H_RANK` float DEFAULT NULL,
  `updated` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`tsloc`) USING BTREE,
  KEY `timestamp` (`timestamp`),
  KEY `location` (`location`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Data exporting was unselected.

-- Dumping structure for table weatherdata.users
CREATE TABLE IF NOT EXISTS `users` (
  `apikey` varchar(100) NOT NULL,
  `mellonuser` varchar(100) NOT NULL,
  `uses` int(11) DEFAULT 0,
  `lastUsed` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `banned` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`apikey`),
  KEY `mellonuser` (`mellonuser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Data exporting was unselected.

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
