<?php

namespace Desk\Response\Customers;

use Desk\Response\AbstractListResponse;

class ListCustomersResponse extends AbstractListResponse
{
	protected $entryClass = 'Desk\Response\Customers\CustomerResponse';
}