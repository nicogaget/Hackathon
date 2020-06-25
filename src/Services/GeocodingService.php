<?php


namespace App\Services;

use Symfony\Component\HttpClient\HttpClient;

class GeocodingService
{
    private $client;

    private $baseUrl = "https://api.mapbox.com/geocoding/v5/mapbox.places/";

    private $API_TOKEN = "pk.eyJ1Ijoic2NhbWFuZGVyIiwiYSI6ImNrYnVzZDNwZzBtc24ycnF6OTk3d2I3aGUifQ.JwhJu4H3ab-durX82JuN0Q";

    public function __construct()
    {
        $this->client = HttpClient::create();
    }

    public function addresstoGPS()
    {
        $response = $this->client->request('GET', "https://api.mapbox.com/geocoding/v5/mapbox.places/17%20Quai%20Arloing.json?access_token=pk.eyJ1Ijoic2NhbWFuZGVyIiwiYSI6ImNrYnVzZDNwZzBtc24ycnF6OTk3d2I3aGUifQ.JwhJu4H3ab-durX82JuN0Q");
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
