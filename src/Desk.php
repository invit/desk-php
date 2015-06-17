<?php

namespace Desk;

class Desk
{
    protected $client;

    protected $apis = [];

    /**
     * @param string $domain
     * @param string $email
     * @param string $password
     */
    public function __construct($domain, $email = null, $password = null, $consumerKey = null, $consumerSecret = null, $token = null, $tokenSecret = null)
    {
        $this->client = new Client($domain, $email, $password, $consumerKey, $consumerSecret, $token, $tokenSecret);
    }

    public function __get($name)
    {
       $allowed = array(
           'customers',
           'cases',
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

    public function cases()
    {
        return $this->getApi('Cases');
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
