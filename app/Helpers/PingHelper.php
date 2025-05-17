<?php
namespace App\Helpers;

class PingHelper
{
    /**
     * Prüft, ob der Host auf dem angegebenen Port erreichbar ist.
     *
     * @param string $host Hostname oder IP
     * @param int $port Portnummer
     * @param int $timeout Timeout in Sekunden
     * @return bool
     */
    public static function isPortOpen(string $host, int $port, int $timeout = 3): bool
    {
        $connection = @fsockopen($host, $port, $errno, $errstr, $timeout);

        if (is_resource($connection)) {
            fclose($connection);
            return true; // Port offen und erreichbar
        }

        return false; // Nicht erreichbar oder Port zu
    }
}
