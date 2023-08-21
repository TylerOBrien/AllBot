<?php

namespace App\Exceptions\Api\v1\Identity;

use App\Exceptions\Api\ApiException;

class IdentityIsntOAuth extends ApiException
{
    /**
     * Create a new exception.
     *
     * @param  int  $http_code  The HTTP status code that will be used in the response.
     *
     * @return void
     */
    public function __construct(int $http_code = 422)
    {
        parent::__construct('identity.already-verified', $http_code);
    }
}
