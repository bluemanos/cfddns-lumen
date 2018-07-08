<?php

namespace App\Context;

use GuzzleHttp\Client;

class IpAddress
{
    /** @var Client */
    private $guzzle;

    /**
     * Create a new command instance.
     *
     * @param Client $guzzle
     * @return void
     */
    public function __construct(Client $guzzle)
    {
        $this->guzzle = $guzzle;
    }

    /**
     * @return string
     *
     * @throws \Exception
     */
    public function handle()
    {
        $response = $this->guzzle->get(env('IPADDRESS_SERVICE'));

        if ($response->getStatusCode() !== 200) {
            throw new \Exception('Cant get an IP address currently.');
        }

        return $response->getBody()->getContents();
    }
}