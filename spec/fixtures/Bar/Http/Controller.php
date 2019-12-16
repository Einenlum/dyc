<?php

namespace Fixtures\Bar\Http;

use Fixtures\Bar\Github\Client;

class Controller
{
    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getStatsActon(string $packageName): int
    {
        return $this->client->getStatsForPackage($packageName);
    }
}
