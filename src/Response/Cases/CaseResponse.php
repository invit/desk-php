<?php

namespace Desk\Response\Cases;

use Desk\Response\AbstractItem;

class CaseResponse extends AbstractItem
{
    protected $dates = [
        'active_at',
        'changed_at',
        'created_at',
        'updated_at',
        'first_opened_at',
        'opened_at',
        'first_resolved_at',
        'resolved_at',
        'received_at',
    ];

    public function __construct($data)
    {
        parent::__construct($data);
    }
}
