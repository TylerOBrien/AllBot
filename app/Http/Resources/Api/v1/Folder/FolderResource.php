<?php

namespace App\Http\Resources\Api\v1\Folder;

use App\Models\Folder;

use Illuminate\Http\Resources\Json\JsonResource;

class FolderResource extends JsonResource
{
    /**
     * Instantiate the resource.
     *
     * @param  \App\Models\Folder  $folder
     *
     * @return void
     */
    public function __construct(Folder $folder)
    {
        parent::__construct($folder);
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'data' => $this->resource,
        ];
    }
}
