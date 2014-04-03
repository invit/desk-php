<?php

namespace Desk\Tests\Objects;

use Desk\Tests\DeskTestCase;

class CustomerEntryTest extends DeskTestCase
{
    protected $customer;

    function setUp()
    {
        parent::setUp();

        $this->customer = $this->desk->customer('/api/v2/customers/1');

        $this->mock('CustomerEntryTest');
    }

    function testGet()
    {
        $this->customer->get();
        $request = $this->history->getLastRequest();

        $this->assertInstanceOf('Desk\Objects\CustomerEntry', $this->customer);
        $this->assertEquals('/api/v2/customers/1', $request->getPath());

        $this->assertSame(1, $this->customer->id);
        $this->assertEquals('John', $this->customer->firstName);
        $this->assertInstanceOf('Carbon\Carbon', $this->customer->createdAt);

        $this->assertEquals(2, $this->customer->emails->count());
        $this->assertEquals(['john@acme.com', 'john@home.com'], $this->customer->emails->values);
        $this->assertEquals('john@acme.com', $this->customer->emails->work);
        $this->assertNull($this->customer->emails->mobile);

        $this->assertEquals(1, $this->customer->phoneNumbers->count());
        $this->assertEquals(['123-456-7890'], $this->customer->phoneNumbers->values);
        $this->assertEquals('123-456-7890', $this->customer->phoneNumbers->work);
        $this->assertNull($this->customer->phoneNumbers->home);

        $this->assertEquals(1, $this->customer->addresses->count());
        $this->assertEquals(['123 Main St, San Francisco, CA 94105'], $this->customer->addresses->values);
        $this->assertEquals('123 Main St, San Francisco, CA 94105', $this->customer->addresses->work);
        $this->assertNull($this->customer->addresses->home);

        $this->assertEquals('vip', $this->customer->customFields->level);
    }
}