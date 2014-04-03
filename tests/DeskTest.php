<?php

namespace Desk\Tests;

use Desk\Desk;

class DeskTest extends DeskTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testInstantiation()
    {
        $customer = $this->desk->customer();
        $this->assertInstanceOf('Desk\Objects\CustomerEntry', $customer);
    }
}
