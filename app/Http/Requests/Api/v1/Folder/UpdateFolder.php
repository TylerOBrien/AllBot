<?php

namespace App\Http\Requests\Api\v1\Folder;

use App\Http\Requests\Api\v1\ApiRequest;
use App\Models\Folder;

class UpdateFolder extends ApiRequest
{
    /**
     * Create a new request.
     *
     * @return void
     */
    public function __construct()
    {
        $this->ability = 'update';
        $this->binding = 'folder';
        $this->model = Folder::class;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'string',
            'parent_folder_id' => 'nullable|int|exists:folders,id',
        ];
    }
}
