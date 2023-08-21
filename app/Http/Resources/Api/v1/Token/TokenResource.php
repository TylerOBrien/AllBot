<?php

namespace App\Http\Resources\Api\v1\Token;

use App\Support\Token\TokenPlaintextPair;

use Illuminate\Http\Resources\Json\JsonResource;

class TokenResource extends JsonResource
{
    /**
     * Instantiate the resource.
     *
     * @param  \App\Support\Token\TokenPlaintextPair  $pair
     *
     * @return void
     */
    public function __construct(TokenPlaintextPair $pair)
    {
        parent::__construct($pair);
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
            'value' => $this->pat->id . '|' . $this->plaintext,
            'ttl' => config('api.bearer.ttl'),
            'ttl_type' => 'minute',
            'created_at' => $this->pat->created_at,
        ];
    }
}
