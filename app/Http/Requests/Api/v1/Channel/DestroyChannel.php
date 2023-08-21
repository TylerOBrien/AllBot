<?php

namespace App\Http\Requests\Api\v1\Channel;

use App\Http\Requests\Api\v1\ApiRequest;
use App\Models\Channel;

class DestroyChannel extends ApiRequest
{
    /**
     * Instantiate the request.
     *
     * @return void
     */
    public function __construct()
    {
        $this->ability = 'destroy';
        $this->binding = 'channel';
        $this->model = Channel::class;
    }
}
