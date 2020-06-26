<?php


namespace App\Services;

use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpKernel\KernelInterface;

class GeoService
{
    public function calcDistance(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        if (($lat1 === $lat2) && ($lng1 === $lng2)) {
            return 0;
        }
        $theta = $lng1 - $lng2;
        $dist = sin(deg2rad((float)$lat1)) * sin(deg2rad((float)$lat2)) + cos(deg2rad((float)$lat1)) * cos(deg2rad((float)$lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $km = round($miles * 1.609344, 2);
        return $km;
    }

}

