<?php

namespace App\Http\Resources\Api\v1\Command;

use App\Models\Command;

use Illuminate\Http\Resources\Json\JsonResource;

class CommandResource extends JsonResource
{
    /**
     * Instantiate the resource.
     *
     * @param  \App\Models\Command  $command
     *
     * @return void
     */
    public function __construct(Command $command)
    {
        parent::__construct($command);
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
