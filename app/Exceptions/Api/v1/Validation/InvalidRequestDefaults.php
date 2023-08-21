<?php

namespace App\Exceptions\Api\v1\Validation;

use App\Exceptions\Api\ApiException;

class InvalidRequestDefaults extends ApiException
{
    /**
     * Create a new exception.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct('validation.invalid-request-defaults', 500);
    }
}
