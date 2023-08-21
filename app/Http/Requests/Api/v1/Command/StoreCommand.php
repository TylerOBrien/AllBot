<?php

namespace App\Http\Requests\Api\v1\Command;

use App\Http\Requests\Api\v1\ApiRequest;
use App\Models\Command;
use App\Traits\Requests\Api\v1\HasOwnership;

class StoreCommand extends ApiRequest
{
    use HasOwnership;

    /**
     * Instantiate the request.
     *
     * @return void
     */
    public function __construct()
    {
        $this->ability = 'command';
        $this->model = Command::class;
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
