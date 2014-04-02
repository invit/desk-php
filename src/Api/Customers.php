<?php

namespace Desk\Api;

class Customers extends AbstractApi
{
	const CUSTOMER_RESPONSE_CLASS = 'Desk\Response\Customers\CustomerResponse';
    const LIST_CUSTOMERS_RESPONSE_CLASS = 'Desk\Response\Customers\ListCustomersResponse';

    public function all()
    {
        return $this->client->get('customers', [], self::LIST_CUSTOMERS_RESPONSE_CLASS);
    }

    public function get()
    {
        return $this->client->get($this->identifier, [], self::CUSTOMER_RESPONSE_CLASS);
    }

    public function search($params = [])
    {
        return $this->client->get('customers/search', $params, self::LIST_CUSTOMERS_RESPONSE_CLASS);
    }

    public function create($data = [])
    {
        return $this->client->post('customers', $data, self::CUSTOMER_RESPONSE_CLASS);
    }

    public function update($data = [])
    {
        return $this->client->patch($this->identifier, $data, self::CUSTOMER_RESPONSE_CLASS);
    }

    public function cases($params = [])
    {
        return $this->client->get($this->identifier . '/cases', $params, self::LIST_CUSTOMERS_RESPONSE_CLASS);
    }
}