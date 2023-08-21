<?php

namespace App\Http\Requests\Api\v1\Sound;

use App\Http\Requests\Api\v1\ApiRequest;
use App\Models\Sound;

class DestroyVideo extends ApiRequest
{
    /**
     * Instantiate the request.
     *
     * @return void
     */
    public function __construct()
    {
        $this->ability = 'destroy';
        $this->binding = 'sound';
        $this->model = Sound::class;
    }
}
