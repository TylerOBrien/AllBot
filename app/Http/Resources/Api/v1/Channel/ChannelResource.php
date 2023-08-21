<?php

namespace App\Http\Resources\Api\v1\Channel;

use App\Models\Channel;

use Illuminate\Http\Resources\Json\JsonResource;

class ChannelResource extends JsonResource
{
    /**
     * Instantiate the resource.
     *
     * @param  \App\Models\Channel  $channel
     *
     * @return void
     */
    public function __construct(Channel $channel)
    {
        parent::__construct($channel);
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
