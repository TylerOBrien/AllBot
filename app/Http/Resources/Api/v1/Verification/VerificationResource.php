<?php

namespace App\Http\Resources\Api\v1\Verification;

use App\Models\Verification;

use Illuminate\Http\Resources\Json\JsonResource;

class VerificationResource extends JsonResource
{
    /**
     * Instantiate the resource.
     *
     * @param  \App\Models\Verification  $verification
     *
     * @return void
     */
    public function __construct(Verification $verification)
    {
        parent::__construct($verification);
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'data' => $this->resource->toArray(),
        ];
    }
}
