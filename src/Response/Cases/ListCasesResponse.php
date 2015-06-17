<?php

namespace Desk\Response\Cases;

use Desk\Response\AbstractListResponse;

class ListCasesResponse extends AbstractListResponse
{
    protected $entryClass = 'Desk\Response\Cases\CaseResponse';
}
