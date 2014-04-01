<?php

namespace Desk\Api;

class Customers extends AbstractApi
{
    public function all()
    {
        return $this->client->get('customers');
    }

    public function get()
    {
        return $this->client->get($this->identifier);
    }

    public function search($params = [])
    {
        return $this->client->get('customers/search', $params);
    }

    public function create($data = [])
    {
        return $this->client->post('customers', $data);
    }

    public function update($data = [])
    {
        return $this->client->patch($this->identifier, $data);
    }

    public function cases($params = [])
    {
        return $this->client->get($this->identifier . '/cases', $params);
    }
}