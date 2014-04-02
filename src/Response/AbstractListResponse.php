<?php

namespace Desk\Response;

use Desk\Response\Customers\CustomerResponse;

abstract class AbstractListResponse implements \Countable
{
	protected $data;
	protected $entries = [];
	protected $entryClass;

	public function __construct($data)
	{
		$this->data = $data;

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
}