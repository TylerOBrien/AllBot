<?php

namespace App\Http\Requests\Api\v1\Folder;

use App\Http\Requests\Api\v1\ApiRequest;
use App\Models\Folder;

class DestroyFolder extends ApiRequest
{
    /**
     * Create a new request.
     *
     * @return void
     */
    public function __construct()
    {
        $this->ability = 'destroy';
        $this->binding = 'folder';
        $this->model = Folder::class;
    }
}
