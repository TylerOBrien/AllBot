<?php

namespace App\Http\Requests\Api\v1\Image;

use App\Http\Requests\Api\v1\ApiRequest;
use App\Models\Image;
use App\Traits\Requests\Api\v1\HasQueryConstraints;

class IndexImage extends ApiRequest
{
    use HasQueryConstraints;

    /**
     * Instantiate the request.
     *
     * @return void
     */
    public function __construct()
    {
        $this->ability = 'index';
        $this->model = Image::class;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string>
     */
    public function rules(): array
    {
        return $this->indexRules();
    }
}
