<?php

namespace App\Http\Requests\Api\v1\Folder;

use App\Http\Requests\Api\v1\ApiRequest;
use App\Models\Folder;

class IndexFolder extends ApiRequest
{
    /**
     * Create a new request.
     *
     * @return void
     */
    public function __construct()
    {
        $this->ability = 'index';
        $this->model = Folder::class;
    }
}
