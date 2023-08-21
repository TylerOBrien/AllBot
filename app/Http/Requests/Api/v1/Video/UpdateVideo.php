<?php

namespace App\Http\Requests\Api\v1\Video;

use App\Http\Requests\Api\v1\ApiRequest;
use App\Models\Video;
use App\Traits\Requests\Api\v1\HasOwnership;

class UpdateVideo extends ApiRequest
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
        $this->binding = 'video';
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
            'owner_id' => 'required_with:owner_type|morphable',
            'owner_type' => 'required_with:owner_id|morphable'
        ];
    }
}
