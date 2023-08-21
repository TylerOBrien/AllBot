<?php

namespace App\Http\Resources\Api\v1\Sound;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;

class SoundIndexResource extends JsonResource
{
    /**
     * Instantiate the resource.
     *
     * @param  \Illuminate\Database\Eloquent\Collection<\App\Models\Sound>|\Illuminate\Pagination\LengthAwarePaginator  $sounds
     *
     * @return void
     */
    public function __construct(Collection|LengthAwarePaginator $sounds)
    {
        parent::__construct($sounds);
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
