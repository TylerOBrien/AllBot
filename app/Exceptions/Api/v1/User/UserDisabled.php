<?php

namespace App\Exceptions\Api\v1\User;

use App\Exceptions\Api\ApiException;

class UserDisabled extends ApiException
{
    /**
     * Create a new exception.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct('user.disabled', 403);
    }
}
