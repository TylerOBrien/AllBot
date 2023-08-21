<?php

namespace App\Http\Resources\Api\v1\Video;

use App\Models\Video;
use Illuminate\Http\Resources\Json\JsonResource;

class VideoResource extends JsonResource
{
    /**
     * Instantiate the resource.
     *
     * @param  \App\Models\Video  $video
     *
     * @return void
     */
    public function __construct(Video $videos)
    {
        parent::__construct($videos);
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
