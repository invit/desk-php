<?php

namespace Desk\Objects;

use Carbon\Carbon;
use ICanBoogie\Inflector;

abstract class AbstractEntry
{
    protected $client;
    protected $data = null;
    protected $id = null;
    protected $inflector;
    protected $contactFields = ['emails', 'phone_numbers', 'addresses'];

    public function __construct($data = null, $client)
    {
        $this->inflector = Inflector::get();

        $this->client = $client;

        if (is_string($data)) {
            $this->id = $data;
        } else {
            $this->data = $data;
        }
    }

    public function get()
    {
        if (is_null($this->data)) {
            $this->data = $this->client->get($this->id);
        }

        $this->id = $this->data['_links']['self']['href'];
    }

    public function __get($name)
    {
        $name = $this->inflector->underscore($name);

        if (array_key_exists($name, $this->data)) {
            return $this->castValue($name);
        }

        if ($name === 'identifier') {
            return $this->id;
        }

        throw new \UnexpectedValueException(sprintf('Invalid property: %s', $name));
    }

    /**
     * @param string $name
     */
    protected function castValue($name)
    {
        $value = $this->data[$name];

        if (preg_match('/_at$/', $name)) {
            return new Carbon($value);
        }

        if (in_array($name, $this->contactFields)) {
            return new ContactList($value);
        }

        if (is_array($value) && count($value)) {
            return $this->castArray($value);
        }

        return $value;
    }

    protected function castArray($value)
    {
        if (is_string(array_keys($value)[0])) {
            return new GenericEntry($value, $this->client);
        }

        return array_map(function ($item) {
            return new GenericEntry($item, $this->client);
        }, $value);
    }
}
