<?php

namespace App\Http\Requests\Api\v1\Video;

use App\Http\Requests\Api\v1\ApiRequest;
use App\Models\Video;

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
        $this->binding = 'video';
        $this->model = Video::class;
    }
}
