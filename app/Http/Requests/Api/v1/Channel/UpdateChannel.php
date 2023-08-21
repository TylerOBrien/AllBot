<?php

namespace App\Http\Requests\Api\v1\Channel;

use App\Http\Requests\Api\v1\ApiRequest;
use App\Models\Channel;
use App\Traits\Requests\Api\v1\HasOwnership;

class UpdateChannel extends ApiRequest
{
    use HasOwnership;

    /**
     * Instantiate the request.
     *
     * @return void
     */
    public function __construct()
    {
        $this->ability = 'update';
        $this->binding = 'channel';
        $this->model = Channel::class;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}
