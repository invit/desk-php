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
}
