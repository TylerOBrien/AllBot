<?php

namespace App\Exceptions\Api\v1\OAuth;

use App\Exceptions\Api\ApiException;

class ProviderNotFound extends ApiException
{
    /**
     * Create a new exception.
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct('oauth.provider.not-found', 404);
    }
}
