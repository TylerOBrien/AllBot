<?php

namespace App\Http\Requests\Api\v1\Folder;

use App\Http\Requests\Api\v1\ApiRequest;
use App\Models\Folder;

class ShowFolder extends ApiRequest
{
    /**
     * Create a new request.
     *
     * @return void
     */
    public function __construct()
    {
        $this->ability = 'show';
        $this->model = Folder::class;
        $this->binding = 'folder';
    }
}
