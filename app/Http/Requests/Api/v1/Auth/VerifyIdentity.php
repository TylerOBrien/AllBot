<?php

namespace App\Http\Requests\Api\v1\Auth;

use App\Http\Requests\Api\v1\ApiRequest;
use App\Traits\Requests\Api\v1\HasIdentity;

class VerifyIdentity extends ApiRequest
{
    use HasIdentity;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $rules = [
            'type' => 'required|string|in:' . join(',', config('enum.verification.type')),
            'value' => 'required|string',
        ];

        if ($this->has('identity_id')) {
            return array_merge($rules, [
                'identity_id' => $this->identityIdRule(),
            ]);
        }

        return array_merge($rules, [
            'identity.type' => $this->identityTypeRule(),
            'identity.value' => $this->identityValueRule(),
        ]);
    }
}
