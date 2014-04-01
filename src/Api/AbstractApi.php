<?php

namespace Desk\Api;

use Desk\Client;

abstract class AbstractApi
{
    protected $client;
    protected $identifier;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
        return $this;
    }
}