<?php

namespace Desk\Tests\Objects;

use Desk\Tests\DeskTestCase;

class CustomerListTest extends DeskTestCase
{
    protected $list;

    function setUp()
    {
        parent::setUp();

        $this->list = $this->desk->customerList();

        $this->mock('CustomerListTest');
    }

    function testGet()
    {
        $this->list->get();
        $request = $this->history->getLastRequest();

        $this->assertInstanceOf('Desk\Objects\CustomerList', $this->list);
        $this->assertEquals('/api/v2/customers', $request->getPath());
    }

    function testCountable()
    {
        $this->list->get();
        $request = $this->history->getLastRequest();

        $this->assertEquals(2, $this->list->count());
    }

    function testArrayAccess()
    {
        $this->list->get();
        $request = $this->history->getLastRequest();

        $this->assertInstanceOf('Desk\Objects\CustomerEntry', $this->list[0]);
    }

    function testIterator()
    {
        $this->list->get();
        $request = $this->history->getLastRequest();

        $this->assertInstanceOf('Iterator', $this->list);

        foreach ($this->list as $index => $customer) {
            $this->assertInstanceOf('Desk\Objects\CustomerEntry', $customer);
        }
    }

    function testSearch()
    {
        $this->list->searchFor('email', 'john@acme.com')->get();
        $request = $this->history->getLastRequest();

        $this->assertEquals('/api/v2/customers/search', $request->getPath());
        $this->assertEquals('email=john%40acme.com', (string) $request->getQuery());
    }

    function testSearchArray()
    {
        $this->list->searchFor('email', ['john@acme.com', 'jack@acme.com'])->get();
        $request = $this->history->getLastRequest();

        $this->assertEquals('email=john%40acme.com%2Cjack%40acme.com', (string) $request->getQuery());
    }

    function testSearchQ()
    {
        $this->list->searchFor('jack@acme.com')->get();
        $request = $this->history->getLastRequest();

        $this->assertEquals('q=jack%40acme.com', (string) $request->getQuery());
    }

    function testPage()
    {
        $this->list->setPage(2)->get();
        $request = $this->history->getLastRequest();

        $this->assertEquals('page=2', (string) $request->getQuery());
    }
}