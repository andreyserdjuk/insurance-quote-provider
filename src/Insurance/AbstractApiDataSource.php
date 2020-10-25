<?php

declare(strict_types=1);

namespace InsuranceTools\Insurance;

use GuzzleHttp\ClientInterface;

abstract class AbstractApiDataSource
{
    protected string $rootUrl;

    protected ClientInterface $client;

    public function __construct(string $rootUrl, ClientInterface $client)
    {
        $this->rootUrl = rtrim($rootUrl, '/');
        $this->client = $client;
    }
}
