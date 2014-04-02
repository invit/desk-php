<?php

namespace Desk\Tests;

use Desk\Desk;

class DeskTestCase extends \PHPUnit_Framework_TestCase
{
    protected $desk;

    protected function setUp()
    {
        parent::setUp();

        $this->desk = new Desk('domain', 'john@doe.com', 'password');
    }
}
