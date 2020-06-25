<?php


namespace App\Services;

use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpKernel\KernelInterface;

class GeocodingService
{
    private $client;
    private $rootPath;
    private $baseUrl = "https://api.mapbox.com/geocoding/v5/mapbox.places/";

    private $API_TOKEN = "pk.eyJ1Ijoic2NhbWFuZGVyIiwiYSI6ImNrYnVzZDNwZzBtc24ycnF6OTk3d2I3aGUifQ.JwhJu4H3ab-durX82JuN0Q";

    public function __construct(string $rootPath)
    {
        $this->client = HttpClient::create();
        // you can also load several files
        $dotenv = new Dotenv();
        var_dump($rootPath);
        $this->rootPath = $rootPath;
        $dotenv->load($rootPath . '/.env.local');

    }

    public function addresstoGPS(string $address)
    {
        $response = $this->client->request('GET', "https://api.mapbox.com/geocoding/v5/mapbox.places/$address.json?access_token=pk.eyJ1Ijoic2NhbWFuZGVyIiwiYSI6ImNrYnVzZDNwZzBtc24ycnF6OTk3d2I3aGUifQ.JwhJu4H3ab-durX82JuN0Q");
        $result = $response->toArray();
        return $result;
    }

    public function GPStoadress()
    {
        $response = $this->client->request('GET', "https://api.mapbox.com/geocoding/v5/mapbox.places/chester.json?proximity=4.810245,45.772796&access_token=pk.eyJ1Ijoic2NhbWFuZGVyIiwiYSI6ImNrYnVzZDNwZzBtc24ycnF6OTk3d2I3aGUifQ.JwhJu4H3ab-durX82JuN0Q");
        $result = $response->toArray();
        return $result;
    }
}
