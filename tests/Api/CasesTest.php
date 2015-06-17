<?php

namespace Desk\Tests\Api;

use Desk\Tests\DeskTestCase;
use GuzzleHttp\Subscriber\Mock;
use GuzzleHttp\Subscriber\History;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Stream\Stream;

class CasesTest extends DeskTestCase
{
    protected $history;
    protected $client;
    protected $mock;

    public function setUp()
    {
        parent::setUp();

        $this->client = $this->desk->getHttpClient();

        $this->mock = new Mock();
        $this->history = new History();

        $this->client->getEmitter()->attach($this->mock);
        $this->client->getEmitter()->attach($this->history);
    }

    protected function mock($jsonString = '')
    {
        $this->mock->addResponse(new Response(
            200,
            array(),
            Stream::factory($jsonString)
        ));
    }

    public function testGetCase()
    {
        $this->mock('{"id":145,"external_id":null,"blurb":"test message","priority":4,"locked_until":null,"active_at":"2015-06-13T05:38:11Z","changed_at":"2015-06-13T05:38:11Z","created_at":"2015-06-13T05:38:11Z","updated_at":"2015-06-13T05:38:11Z","_links":{"self":{"href":"/api/v2/cases/145","class":"case"}}}');
        $case = $this->desk->cases->setIdentifier('/api/v2/cases/145')->get();
        $request = $this->history->getLastRequest();

        $this->assertEquals('/api/v2/cases/145', $request->getPath());
        $this->assertEquals('GET', $request->getMethod());

        $this->assertInstanceOf('Desk\Response\Cases\CaseResponse', $case);
        $this->assertInstanceOf('Carbon\Carbon', $case->createdAt);
        $this->assertInstanceOf('Carbon\Carbon', $case->updatedAt);
        $this->assertEquals('/api/v2/cases/145', $case->identifier);
        $this->assertEquals('test message', $case->blurb);
    }

    public function testGetCaseList()
    {
        $this->mock('{"total_entries":1,"page":1,"_links":{"self":{"href":"/api/v2/cases?page=1&per_page=50","class":"page"},"first":{"href":"/api/v2/cases?page=1&per_page=50","class":"page"},"last":{"href":"/api/v2/cases?page=5204&per_page=50","class":"page"},"previous":null,"next":{"href":"/api/v2/cases?page=2&per_page=50","class":"page"}},"_embedded":{"entries":[{"id":"not needed in here because other things are tested"}]}}');
        $all = $this->desk->cases->all();
        $request = $this->history->getLastRequest();

        $this->assertInstanceOf('Desk\Response\Cases\ListCasesResponse', $all);
        $this->assertEquals(1, $all->count());
        $this->assertEquals(1, count($all->getItems()));
        $this->assertInstanceOf('Desk\Response\Cases\CaseResponse', $all->getItems()[0]);

        $this->assertEquals('/api/v2/cases', $request->getPath());
        $this->assertEquals('GET', $request->getMethod());
    }

    public function testSearchCases()
    {
        $this->mock('{"total_entries":1,"page":1,"_links":{"self":{"href":"/api/v2/cases/search?subject=important&page=1&per_page=20","class":"page"},"first":{"href":"/api/v2/cases/search?subject=important&page=1&per_page=20","class":"page"},"last":{"href":"/api/v2/cases/search?email=important&page=1&per_page=20","class":"page"},"previous":null,"next":null},"_embedded":{"entries":[{"id":"123"}]}}');
        $results = $this->desk->cases->search([ 'subject' => 'important']);
        $request = $this->history->getLastRequest();

        $this->assertInstanceOf('Desk\Response\Cases\ListCasesResponse', $results);
        $this->assertEquals(1, $results->count());
        $this->assertEquals(1, count($results->getItems()));
        $this->assertEquals('/api/v2/cases/search', $request->getPath());
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('https://domain.desk.com/api/v2/cases/search?subject=important', $request->getUrl());
        $this->assertEquals('subject=important', (string) $request->getQuery());

        $this->assertInstanceOf('Desk\Response\Cases\CaseResponse', $results[0]);

        foreach ($results as $case) {
            $this->assertEquals('123', $case->id);
        }
    }

    public function testCreateCase()
    {
        $this->mock();

        $data = json_decode('{"type":"email","subject":"Email Case Subject","priority":4,"status":"open","labels": ["Spam", "Ignore"],"message":{"direction": "in", "body": "Example body","to":"someone@desk.com","from":"someone-else@desk.com","subject":"My email subject"},"_links":{"customer":{"class":"customer","href":"/api/v2/customers/1"}}}');

        $this->desk->cases->create($data);
        $request = $this->history->getLastRequest();

        $this->assertEquals('/api/v2/cases', $request->getPath());
        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals(json_encode($data), $request->getBody());
    }

    public function testUpdateCase()
    {
        $this->mock();

        $data = json_decode('{"subject":"Updated Subject","_links":{"assigned_group":{"class":"group","href":"/api/v2/groups/1"}}}');

        $this->desk->cases->setIdentifier('/api/v2/cases/111111')->update($data);
        $request = $this->history->getLastRequest();

        $this->assertEquals('/api/v2/cases/111111', $request->getPath());
        $this->assertEquals('PATCH', $request->getMethod());
        $this->assertEquals(json_encode($data), $request->getBody());

        $this->mock();
        $this->desk->cases->update($data);
        $request = $this->history->getLastRequest();

        $this->assertEquals('/api/v2/cases/111111', $request->getPath());
    }
}
