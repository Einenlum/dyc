<?php

namespace Fixtures\Bar\Github;

class Client
{
    /**
     * @var string
     */
    private $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function getStatsForPackage(string $packageName): int
    {
        return 124;
    }
}
