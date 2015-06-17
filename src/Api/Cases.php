<?php

namespace Desk\Api;

class Cases extends AbstractApi
{
    const CASE_RESPONSE_CLASS = 'Desk\Response\Cases\CaseResponse';
    const LIST_CASES_RESPONSE_CLASS = 'Desk\Response\Cases\ListCasesResponse';

    public function all()
    {
        return $this->client->get('cases', [], self::LIST_CASES_RESPONSE_CLASS);
    }

    public function get()
    {
        return $this->client->get($this->identifier, [], self::CASE_RESPONSE_CLASS);
    }

    public function search($params = [])
    {
        return $this->client->get('cases/search', $params, self::LIST_CASES_RESPONSE_CLASS);
    }

    public function create($data = [])
    {
        return $this->client->post('cases', $data, self::CASE_RESPONSE_CLASS);
    }

    public function update($data = [])
    {
        return $this->client->patch($this->identifier, $data, self::CASE_RESPONSE_CLASS);
    }
}
