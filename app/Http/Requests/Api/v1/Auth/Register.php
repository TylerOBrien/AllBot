<?php

namespace App\Http\Requests\Api\v1\Auth;

use App\Http\Requests\Api\v1\OAuthRequest;
use App\Schemas\Credentials\CredentialsSchema;
use App\Traits\Requests\Api\v1\HasIdentity;

class Register extends OAuthRequest
{
    use HasIdentity;

    /**
     * Instantiate the request.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return array
     */
    public function defaults(): array
    {
        return [
            'program_id' => $this->getProgram()->id,
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return array_merge(
            CredentialsSchema::getRules($this->all(), CredentialsSchema::REGISTER | CredentialsSchema::IDENTITY_AND_SECRET),
            [
                'first_name' => 'nullable|string',
                'last_name' => 'nullable|string',
            ],
        );
    }
}
