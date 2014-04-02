<?php

namespace Desk\Response;

abstract class AbstractListResponse implements \Countable, \ArrayAccess, \Iterator
{
    protected $data;
    protected $entries = [];
    protected $entryClass;
    protected $position = 0;

    public function __construct($data)
    {
        $this->data = $data;
        $this->position = 0;

        $entries = $this->data['_embedded']['entries'];

        if (count($entries)) {
            $this->entries = array_map(function ($item) {
                return new $this->entryClass($item);
            }, $entries);
        }
    }

    public function count()
    {
        return count($this->entries);
    }

    public function getItems()
    {
        return $this->entries;
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->entries[] = $value;
        } else {
            $this->entries[$offset] = $value;
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->entries[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->entries[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->entries[$offset]) ? (object) $this->entries[$offset] : null;
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function current()
    {
        return $this->entries[$this->position];
    }

    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        ++$this->position;
    }

    public function valid()
    {
        return isset($this->entries[$this->position]);
    }
}
