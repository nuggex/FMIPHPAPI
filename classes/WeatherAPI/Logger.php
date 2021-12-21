<?php


namespace WeatherAPI;

/**
 * Class for logging messages to log file.
 * @property false|resource fh
 */
class Logger
{

    function __construct($file)
    {
        $this->fh = fopen($file, "a") or die("Unable to open: " . $file);
    }

    /**
     * Write message to log.
     * @param string String to write to logfile
     */
    function msg($str)
    {
        $d = date("Y-m-d H:i:s");
        fwrite($this->fh, $d . " " . $str . "\n");
    }

}


