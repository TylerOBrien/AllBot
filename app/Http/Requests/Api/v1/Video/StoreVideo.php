<?php

namespace App\Http\Requests\Api\v1\Video;

use App\Http\Requests\Api\v1\ApiRequest;
use App\Models\Video;
use App\Traits\Requests\Api\v1\HasOwnership;

class StoreVideo extends ApiRequest
{
    use HasOwnership;

    /**
     * Instantiate the request.
     *
     * @return void
     */
    public function __construct()
    {
        $this->ability = 'store';
        $this->model = Video::class;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'owner_id' => 'required|morphable',
            'owner_type' => 'required|morphable'
        ];
    }
}
