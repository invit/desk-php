<?php

namespace Desk\Response\Customers;

use Desk\Response\AbstractItem;

class CustomerResponse extends AbstractItem
{
    protected $dates = [ 'created_at', 'updated_at' ];
    public $emails;

    public function __construct($data)
    {
        $this->emails = new ListCustomerEmails($data['emails']);
        parent::__construct($data);
    }
}
