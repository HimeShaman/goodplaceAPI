<?php


namespace App\Helper;


use GuzzleHttp\Client;

class NominatimClient
{


    private $url;

    /**
     * NominatimClient constructor.
     */
    public function __construct($url)
    {
        $this->url = $url;
    }

    public function retrieveCoordinate($adress) {

        $client = new Client(['verify' => false ]);

        $encoded_adress = urlencode($adress);
        $response = $client->get($this->url."?format=json&addressdetails=1&q={$encoded_adress}&format=json&limit=1");

        $json = json_decode($response->getBody()->getContents(),true);

        $coordinate = null;

        if(isset($json) && count($json) > 0) {
            $coordinate = [
                "lat" => $json[0]["lat"],
                "long" => $json[0]["lon"]
            ];
        }

        return $coordinate;
    }
}
