<?php

namespace Desk\Objects;

abstract class AbstractList implements \Countable, \ArrayAccess, \Iterator
{
    protected $page = 1;
    protected $data;
    protected $client;
    protected $entries = [];
    protected $endpoint;
    protected $entryClass;
    protected $position;
    protected $params = [];
    protected $searching = false;

    public function __construct($client)
    {
        $this->client = $client;
        $this->position = 0;
    }

    public function searchFor($key, $value = null)
    {
        $this->searching = true;

        if (is_null($value)) {
            $this->params['q'] = $key;
        } else {
            if (is_array($value)) {
                $value = join(',', $value);
            }

            $this->params[$key] = $value;
        }

        return $this;
    }

    public function setPage($page)
    {
        $this->page = $page;

        return $this;
    }

    public function count()
    {
        return count($this->entries);
    }

    public function get()
    {
        if ($this->page > 1) {
            $this->params['page'] = $this->page;
        }

        if ($this->searching) {
            $this->endpoint .= '/search';
        }

        $this->data = $this->client->get($this->endpoint, $this->params);

        $this->entries = array_map(function ($entry) {
            return new $this->entryClass($entry, $this->client);
        }, $this->data['_embedded']['entries']);

        return $this;
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
        return isset($this->entries[$offset]) ? $this->entries[$offset] : null;
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