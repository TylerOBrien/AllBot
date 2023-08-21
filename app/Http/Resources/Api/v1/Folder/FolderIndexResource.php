<?php

namespace App\Http\Resources\Api\v1\Folder;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;

class FolderIndexResource extends JsonResource
{
    /**
     * Instantiate the resource.
     *
     * @param  \Illuminate\Database\Eloquent\Collection<\App\Models\Folder>|\Illuminate\Pagination\LengthAwarePaginator  $folders
     *
     * @return void
     */
    public function __construct(Collection|LengthAwarePaginator $folders)
    {
        parent::__construct($folders);
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
        if ($this->resource instanceof LengthAwarePaginator) {
            $array = $this->resource->toArray();
        } else {
            $array = ['data' => $this->resource->toArray()];
        }

        return $array;
    }
}
