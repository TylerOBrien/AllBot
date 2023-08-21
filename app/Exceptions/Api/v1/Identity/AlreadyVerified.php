<?php

namespace App\Exceptions\Api\v1\Identity;

use App\Exceptions\Api\ApiException;

class AlreadyVerified extends ApiException
{
    /**
     * Create a new exception.
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct('identity.verification.already', 422);
    }
}
