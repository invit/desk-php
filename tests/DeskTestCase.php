<?php

namespace Desk\Tests;

use Desk\Desk;
use GuzzleHttp\Subscriber\Mock;
use GuzzleHttp\Subscriber\History;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Stream\Stream;

class DeskTestCase extends \PHPUnit_Framework_TestCase
{
    protected $desk;
    protected $client;
    protected $mock;
    protected $history;

    protected function setUp()
    {
        parent::setUp();

        $this->desk = new Desk('domain', 'john@doe.com', 'password');

        $this->client = $this->desk->getHttpClient();

        $this->mock = new Mock();
        $this->history = new History();

        $this->client->getEmitter()->attach($this->mock);
        $this->client->getEmitter()->attach($this->history);
    }

    protected function mock($mockFile = null)
    {
    	$jsonString = '';

    	if ($mockFile) {
    		$jsonString = file_get_contents(dirname(__FILE__) . "/Mocks/$mockFile.json");
    	}

        $this->mock->addResponse(new Response(
            200,
            array(),
            Stream::factory($jsonString)
        ));
    }
}
