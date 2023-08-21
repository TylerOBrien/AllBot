<?php

namespace App\Exceptions\Api\v1\Identity;

use App\Exceptions\Api\ApiException;

class ExpiredVerificationCode extends ApiException
{
    /**
     * Instantiate the exception.
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct('identity.verification.expired', 422);
    }
}
