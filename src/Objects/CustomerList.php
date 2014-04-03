<?php

namespace Desk\Objects;

class CustomerList extends AbstractList
{
    protected $endpoint = 'customers';
    protected $entryClass = 'Desk\Objects\CustomerEntry';
}
