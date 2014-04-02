<?php

namespace Desk\Response;

use JMS\Serializer\Annotation\Type;

abstract class AbstractListResponse
{
	/**
     * @Type("integer")
     * @var int
     */
	protected $total_entries;
}