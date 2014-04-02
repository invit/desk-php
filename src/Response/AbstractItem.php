<?php

namespace Desk\Response;

use Carbon\Carbon;
use Desk\Response\GenericData;
use ICanBoogie\Inflector;

abstract class AbstractItem
{
    protected $data;
    protected $inflector;
    protected $dates = [];

    public function __construct($data)
    {
        $this->data = $data;
        $this->inflector = Inflector::get();
    }

    protected function castValue($name)
    {
        $value = $this->data[$name];

        if (in_array($name, $this->dates)) {
            return new Carbon($value);
        }

        if (is_array($value) && count($value)) {
            return $this->castArray($value);
        }

        return $value;
    }

    protected function castArray($value)
    {
        if (is_string(array_keys($value)[0])) {
            return new GenericData($value);
        }

        return array_map(function ($item) {
            return new GenericData($item);
        }, $value);
    }

    public function __get($name)
    {
        $name = $this->inflector->underscore($name);

        if (array_key_exists($name, $this->data)) {
            return $this->castValue($name);
        }

        if ($name === 'identifier') {
            return $this->data['_links']['self']['href'];
        }

        throw new \UnexpectedValueException(sprintf('Invalid property: %s', $name));
    }
}