<?php

namespace App\Http\Requests\Api\v1\Image;

use App\Http\Requests\Api\v1\ApiRequest;
use App\Models\Image;

class ShowImage extends ApiRequest
{
    /**
     * Instantiate the request.
     *
     * @return void
     */
    public function __construct()
    {
        $this->ability = 'show';
        $this->binding = 'image';
        $this->model = Image::class;
    }
}
