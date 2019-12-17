<?php


namespace App\Helper;


use GuzzleHttp\Client;

class NominatimClient
{



    /**
     * NominatimClient constructor.
     */
    public function __construct()
    {
    }

    public function retrieveCoordinate($adress) {

        $client = new Client(['verify' => false ]);

        $encoded_adress = urlencode($adress);

        //Bad ! We must use static constant to store url
        $response = $client->get("https://nominatim.openstreetmap.org?format=json&addressdetails=1&q={$encoded_adress}&format=json&limit=1");

        $json = json_decode($response->getBody()->getContents());

        $coordinate = null;

        if(isset($json) && count($json) > 0) {
            $coordinate = [
                "lat" => $json[0]->lat,
                "long" => $json[0]->lon
            ];
        }

        return $coordinate;
    }
}
