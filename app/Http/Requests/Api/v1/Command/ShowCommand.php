<?php

namespace App\Http\Requests\Api\v1\Command;

use App\Http\Requests\Api\v1\ApiRequest;
use App\Models\Command;

class ShowCommand extends ApiRequest
{
    /**
     * Instantiate the request.
     *
     * @return void
     */
    public function __construct()
    {
        $this->ability = 'show';
        $this->binding = 'command';
        $this->model = Command::class;
    }
}
