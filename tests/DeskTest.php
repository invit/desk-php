<?php

namespace Desk\Tests;

use Desk\Desk;

class DeskTest extends \PHPUnit_Framework_TestCase
{
    public function testGetApi()
    {
        $desk = new Desk('domain', 'john@doe.com', 'password');

        $customers = $desk->customers;
        $this->assertInstanceOf('Desk\Api\Customers', $customers);
    }

    public function testBasicAuth()
    {
        $desk = new Desk('domain', 'john@doe.com', 'password');

        $client = $desk->getHttpClient();
        $request = $client->createRequest('GET');

        $requestConfig = $request->getConfig();
        $headers = $request->getHeaders();

        $this->assertEquals('john@doe.com', $requestConfig['auth'][0]);
        $this->assertEquals('password', $requestConfig['auth'][1]);

        $this->assertEquals('domain.desk.com', $headers['Host'][0]);
    }

    public function testOauth1()
    {
        $desk = new Desk('domain', null, null, 'myconsumerkey', 'myconsumersecret', 'mytoken', 'mytokensecret');

        $client = $desk->getHttpClient();
        $request = $client->createRequest('GET');

        $requestConfig = $request->getConfig();
        $headers = $request->getHeaders();


        $this->assertEquals('oauth', $requestConfig['auth']);
        $this->assertEquals('domain.desk.com', $headers['Host'][0]);
    }
}
