<?php

namespace App\Http\Requests\Api\v1\Auth;

use App\Http\Requests\Api\v1\OAuthRequest;
use App\Schemas\Credentials\CredentialsSchema;
use App\Traits\Requests\Api\v1\HasIdentity;

class Login extends OAuthRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return CredentialsSchema::getRules($this->all(), CredentialsSchema::IDENTITY_AND_SECRET);
    }
}
