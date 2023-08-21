<?php

namespace App\Http\Requests\Api\v1\Sound;

use App\Http\Requests\Api\v1\ApiRequest;
use App\Models\Sound;

class ShowSound extends ApiRequest
{
    /**
     * Instantiate the request.
     *
     * @return void
     */
    public function __construct()
    {
        $this->ability = 'show';
        $this->binding = 'sound';
        $this->model = Sound::class;
    }
}
