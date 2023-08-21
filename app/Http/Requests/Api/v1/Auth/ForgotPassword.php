<?php

namespace App\Http\Requests\Api\v1\Auth;

use App\Http\Requests\Api\v1\ApiRequest;

class ForgotPassword extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        if ($this->has('identity_id')) {
            return [
                'identity_id' => 'required|int|exists:identities,id',
            ];
        }

        return [
            'identity.type' => 'required|string|in:' . join(',', config('enum.identity.type')),
            'identity.value' => 'required|string' . $this->identityRule(),
        ];
    }

    /**
     * Generate the appropriate rule string for the identity based on the given
     * identity type.
     *
     * @return string
     */
    protected function identityRule(): string
    {
        return match ($this->input('identity.type')) {
            'email' => '|email',
            default => '',
        };
    }
}
