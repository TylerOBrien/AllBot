<?php

namespace App\Http\Resources\Api\v1\Video;

use App\Models\Sound;

use Illuminate\Http\Resources\Json\JsonResource;

class SoundResource extends JsonResource
{
    /**
     * Instantiate the resource.
     *
     * @param  \App\Models\Sound  $sound
     *
     * @return void
     */
    public function __construct(Sound $sound)
    {
        parent::__construct($sound);
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
