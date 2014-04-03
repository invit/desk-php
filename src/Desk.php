<?php

namespace Desk;

use Desk\Objects\CustomerEntry;
use Desk\Objects\CustomerList;

class Desk
{
    protected $client;

    protected $apis = [];

    /**
     * @param string $domain
     * @param string $email
     * @param string $password
     */
    public function __construct($domain, $email, $password)
    {
        $this->client = new Client($domain, $email, $password);
    }

    public function getHttpClient()
    {
        return $this->client->httpClient;
    }

    public function customer($data = null)
    {
        return new CustomerEntry($data, $this->client);
    }

    public function customerList()
    {
        return new CustomerList($this->client);
    }
}
