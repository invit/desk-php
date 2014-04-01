<?php

namespace Desk\Tests\Api;

use Desk\Tests\DeskTestCase;
use Desk\Api\Customers;
use GuzzleHttp\Subscriber\Mock;
use GuzzleHttp\Subscriber\History;
use GuzzleHttp\Message\Response;

class CustomersTest extends DeskTestCase
{
    protected $history;

    protected function setUp()
    {
        parent::setUp();

        $client = $this->desk->getHttpClient();

        $mock = new Mock();
        $mock->addResponse(new Response(200));

        $this->history = new History();

        $client->getEmitter()->attach($mock);
        $client->getEmitter()->attach($this->history);
    }

    public function testGetCustomer()
    {
        $this->desk->customers->setIdentifier('/api/v2/customers/111111')->get();
        $request = $this->history->getLastRequest();

        $this->assertEquals('/api/v2/customers/111111', $request->getPath());
        $this->assertEquals('GET', $request->getMethod());
    }

    public function testGetCustomerList()
    {
        $this->desk->customers->all();
        $request = $this->history->getLastRequest();

        $this->assertEquals('/api/v2/customers', $request->getPath());
        $this->assertEquals('GET', $request->getMethod());
    }

    public function testSearchCustomers()
    {
        $this->desk->customers->search([ 'email' => 'jack@doe.com']);
        $request = $this->history->getLastRequest();

        $this->assertEquals('/api/v2/customers/search', $request->getPath());
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('https://domain.desk.com/api/v2/customers/search?email=jack%40doe.com', $request->getUrl());
        $this->assertEquals('email=jack%40doe.com', (string) $request->getQuery());
    }

    public function testCreateCustomer()
    {
        $data = [
            'email' => 'jack@doe.com',
            'first_name' => 'Jack',
            'last_name' => 'Doe',
        ];

        $this->desk->customers->create($data);
        $request = $this->history->getLastRequest();

        $this->assertEquals('/api/v2/customers', $request->getPath());
        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals(json_encode($data), $request->getBody());
    }

    public function testUpdateCustomer()
    {
        $data = [
            'email' => 'jack@doe.com',
            'first_name' => 'Jack',
            'last_name' => 'Doe',
        ];

        $this->desk->customers->setIdentifier('/api/v2/customers/111111')->update($data);
        $request = $this->history->getLastRequest();

        $this->assertEquals('/api/v2/customers/111111', $request->getPath());
        $this->assertEquals('PATCH', $request->getMethod());
        $this->assertEquals(json_encode($data), $request->getBody());
    }

    public function testCustomerCases()
    {
        $this->desk->customers->setIdentifier('/api/v2/customers/111111')->cases();
        $request = $this->history->getLastRequest();

        $this->assertEquals('/api/v2/customers/111111/cases', $request->getPath());
        $this->assertEquals('GET', $request->getMethod());
    }
}