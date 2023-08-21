<?php

namespace App\Http\Requests\Api\v1\Folder;

use App\Http\Requests\Api\v1\ApiRequest;
use App\Models\Folder;

class StoreFolder extends ApiRequest
{
    /**
     * Create a new request.
     *
     * @return void
     */
    public function __construct()
    {
        $this->ability = 'store';
        $this->model = Folder::class;
    }

    /**
     * Get the default values for the request fields.
     *
     * @return array<string, string>
     */
    public function defaults(): array
    {
        return [
            'owner_id' => auth()->id(),
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'parent_folder_id' => 'nullable|int|exists:folders,id',
        ];
    }
}
