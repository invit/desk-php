<?php

namespace Desk\Objects;

class ContactList implements \Countable, \ArrayAccess, \Iterator
{
    protected $data;
    private $position = 0;
    public $values = [];
    protected $types = [];

    public function __construct($data)
    {
        $this->data = $data;
        $this->position = 0;

        $this->values = array_map(function ($item) {
            return $item['value'];
        }, $this->data);

        $this->types = array_map(function ($item) {
            return $item['type'];
        }, $this->data);
    }

    public function __get($name)
    {
        if (in_array($name, ['home', 'work', 'mobile', 'other'])) {
            return in_array($name, $this->types) ? $this->values[array_search($name, $this->types)] : null;
        }

        throw new \UnexpectedValueException(sprintf('Invalid property: %s', $name));
    }

    public function count()
    {
        return count($this->data);
    }

    public function getData()
    {
        return $this->data;
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->data[$offset]) ? (object) $this->data[$offset] : null;
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function current()
    {
        return $this->data[$this->position]['value'];
    }

    public function key()
    {
        return $this->data[$this->position]['type'];
    }

    public function next()
    {
        ++$this->position;
    }

    public function valid()
    {
        return isset($this->data[$this->position]);
    }
}
