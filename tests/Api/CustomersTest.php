<?php

namespace Desk\Tests\Api;

use Desk\Tests\DeskTestCase;
use Desk\Api\Customers;
use GuzzleHttp\Subscriber\Mock;
use GuzzleHttp\Subscriber\History;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Stream\Stream;

class CustomersTest extends DeskTestCase
{
    protected $history;

    protected function mock($jsonString = '')
    {
        $client = $this->desk->getHttpClient();

        $mock = new Mock();
        $mock->addResponse(new Response(
            200,
            array(),
            Stream::factory($jsonString)
        ));

        $this->history = new History();

        $client->getEmitter()->attach($mock);
        $client->getEmitter()->attach($this->history);
    }

    public function testGetCustomer()
    {
        $this->mock('{"id":34528508,"first_name":"Alex","last_name":"Bard","company":"Desk.com","title":"CEO","external_id":null,"background":"Wowing customer","language":null,"locked_until":null,"created_at":"2012-06-08T13:43:06Z","updated_at":"2012-09-09T04:12:12Z","custom_fields":{"premium_user":null},"emails":[{"type":"work","value":"support@desk.com"}],"phone_numbers":[],"addresses":[],"_links":{"self":{"href":"/api/v2/customers/34528508","class":"customer"},"locked_by":null,"company":{"href":"/api/v2/companies/91435","class":"company"},"facebook_user":null,"twitter_user":{"href":"/api/v2/twitter_users/2901244","class":"twitter_user"},"cases":{"href":"/api/v2/customers/34528508/cases","class":"case","count":2}}}');
        $customer = $this->desk->customers->setIdentifier('/api/v2/customers/34528508')->get();
        $request = $this->history->getLastRequest();

        $this->assertEquals('/api/v2/customers/34528508', $request->getPath());
        $this->assertEquals('GET', $request->getMethod());

        $this->assertInstanceOf('Desk\Response\Customers\CustomerResponse', $customer);
        $this->assertInstanceOf('Carbon\Carbon', $customer->createdAt);
        $this->assertInstanceOf('Carbon\Carbon', $customer->updatedAt);
        $this->assertEquals('/api/v2/customers/34528508', $customer->identifier);
        $this->assertEquals('Bard', $customer->lastName);

        $this->assertEquals('work', $customer->emails[0]->type);
        $this->assertEquals('support@desk.com', $customer->emails[0]->value);
        $this->assertEquals(null, $customer->customFields->premiumUser);
        $this->assertInstanceOf('Desk\Response\Customers\ListCustomerEmails', $customer->emails);
        $this->assertEquals(['support@desk.com'], $customer->emails->getAddresses());

        foreach ($customer->emails as $type => $email) {
            $this->assertEquals('work', $type);
            $this->assertEquals('support@desk.com', $email);
        }
    }

    public function testGetCustomerList()
    {
        $this->mock('{"total_entries":1,"page":1,"_links":{"self":{"href":"/api/v2/customers?page=1&per_page=50","class":"page"},"first":{"href":"/api/v2/customers?page=1&per_page=50","class":"page"},"last":{"href":"/api/v2/customers?page=5204&per_page=50","class":"page"},"previous":null,"next":{"href":"/api/v2/customers?page=2&per_page=50","class":"page"}},"_embedded":{"entries":[{"id":34528508,"first_name":"Alex","last_name":"Bard","company":"Desk.com","title":"CEO","external_id":null,"background":"Wowing customer","language":null,"locked_until":null,"created_at":"2012-06-08T13:43:06Z","updated_at":"2012-09-09T04:12:12Z","custom_fields":{"premium_user":null},"emails":[{"type":"work","value":"support@desk.com"}],"phone_numbers":[],"addresses":[],"_links":{"self":{"href":"/api/v2/customers/34528508","class":"customer"},"locked_by":null,"company":{"href":"/api/v2/companies/91435","class":"company"},"facebook_user":null,"twitter_user":{"href":"/api/v2/twitter_users/2901244","class":"twitter_user"},"cases":{"href":"/api/v2/customers/34528508/cases","class":"case","count":2}}}]}}');
        $all = $this->desk->customers->all();
        $request = $this->history->getLastRequest();

        $this->assertInstanceOf('Desk\Response\Customers\ListCustomersResponse', $all);
        $this->assertEquals(1, $all->count());
        $this->assertEquals(1, count($all->getItems()));
        $this->assertInstanceOf('Desk\Response\Customers\CustomerResponse', $all->getItems()[0]);

        $this->assertEquals('/api/v2/customers', $request->getPath());
        $this->assertEquals('GET', $request->getMethod());
    }

    public function testSearchCustomers()
    {
        $this->mock('{"total_entries":1,"page":1,"_links":{"self":{"href":"/api/v2/customers/search?email=support%40desk.com&page=1&per_page=20","class":"page"},"first":{"href":"/api/v2/customers/search?email=support%40desk.com&page=1&per_page=20","class":"page"},"last":{"href":"/api/v2/customers/search?email=support%40desk.com&page=1&per_page=20","class":"page"},"previous":null,"next":null},"_embedded":{"entries":[{"id":34528508,"first_name":"Alex","last_name":"Bard","company":"Desk.com","title":"CEO","external_id":null,"background":"Wowing customer","language":null,"locked_until":null,"created_at":"2012-06-08T13:43:06Z","updated_at":"2012-09-09T04:12:12Z","custom_fields":{"premium_user":null},"emails":[{"type":"work","value":"support@desk.com"}],"phone_numbers":[],"addresses":[],"_links":{"self":{"href":"/api/v2/customers/34528508","class":"customer"},"locked_by":null,"company":{"href":"/api/v2/companies/91435","class":"company"},"facebook_user":null,"twitter_user":{"href":"/api/v2/twitter_users/2901244","class":"twitter_user"},"cases":{"href":"/api/v2/customers/34528508/cases","class":"case","count":2}}}]}}');
        $results = $this->desk->customers->search([ 'email' => 'support@desk.com']);
        $request = $this->history->getLastRequest();

        $this->assertInstanceOf('Desk\Response\Customers\ListCustomersResponse', $results);
        $this->assertEquals(1, $results->count());
        $this->assertEquals(1, count($results->getItems()));
        $this->assertEquals('/api/v2/customers/search', $request->getPath());
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('https://domain.desk.com/api/v2/customers/search?email=support%40desk.com', $request->getUrl());
        $this->assertEquals('email=support%40desk.com', (string) $request->getQuery());

        $this->assertInstanceOf('Desk\Response\Customers\CustomerResponse', $results[0]);

        foreach ($results as $customer) {
            $this->assertEquals('Bard', $customer->lastName);
        }
    }

    public function testCreateCustomer()
    {
        $this->mock();

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
        $this->mock();

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
        $this->mock();

        $this->desk->customers->setIdentifier('/api/v2/customers/111111')->cases();
        $request = $this->history->getLastRequest();

        $this->assertEquals('/api/v2/customers/111111/cases', $request->getPath());
        $this->assertEquals('GET', $request->getMethod());
    }
}
