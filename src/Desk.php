<?php

namespace Desk;

use Desk\Api\Customers;

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

    public function __get($name)
    {
       $allowed = array(
           'customers'
       );

       if (in_array($name, $allowed)) {
           return $this->{$name}();
       }

       throw new \UnexpectedValueException(sprintf('Invalid property: %s', $name));
    }

    public function getHttpClient()
    {
        return $this->client->httpClient;
    }

    public function customers()
    {
        return $this->getApi('Customers');
    }

    /**
     * @param string $class
     */
    protected function getApi($class)
    {
        $class = "\\" . __NAMESPACE__ . "\\Api\\" . $class;
        if (!array_key_exists($class, $this->apis)) {
            $this->apis[$class] = new $class($this->client);
        }
        return $this->apis[$class];
    }
}
