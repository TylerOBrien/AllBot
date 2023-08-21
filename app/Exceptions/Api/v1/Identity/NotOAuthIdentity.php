<?php

namespace App\Exceptions\Api\v1\Auth;

use App\Exceptions\Api\ApiException;

class NotOAuthIdentity extends ApiException
{
    /**
     * Create a new exception.
     *
     * @return voida
     */
    public function __construct()
    {
        parent::__construct('auth.identity.not-oauth', 422);
    }
}
