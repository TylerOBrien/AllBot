<?php

namespace App\Http\Resources\Api\v1\Image;

use App\Models\Image;

use Illuminate\Http\Resources\Json\JsonResource;

class ImageResource extends JsonResource
{
    /**
     * Instantiate the resource.
     *
     * @param  \App\Models\Image  $image
     *
     * @return void
     */
    public function __construct(Image $image)
    {
        parent::__construct($image);
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
