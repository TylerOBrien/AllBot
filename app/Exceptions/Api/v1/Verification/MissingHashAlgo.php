<?php

namespace App\Exceptions\Api\v1\Verification;

use App\Exceptions\Api\ApiException;

class MissingHashAlgo extends ApiException
{
    /**
     * Create a new exception.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct('verification.hash-algo.missing', 422);
    }
}
