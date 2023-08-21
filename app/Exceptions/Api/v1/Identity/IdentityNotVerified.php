<?php

namespace App\Exceptions\Api\v1\Identity;

use App\Exceptions\Api\ApiException;

class IdentityNotVerified extends ApiException
{
    /**
     * Create a new exception.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct('identity.not-verified', 403);
    }
}
