<?php

namespace App\Guards\Api\v1;

use App\Events\Api\v1\Auth\AuthAttempted;
use App\Models\{ Identity, Token, User };
use App\Support\Credentials;
use App\Support\OAuth\OAuthDriver;

use App\Exceptions\Api\v1\User\UserDisabled;
use App\Exceptions\Api\v1\Auth\{
    ExpiredToken,
    InvalidCredentials,
    InvalidToken,
    MissingOAuthProvider,
    MissingSecret,
    MissingToken,
    NotOAuthIdentity,
};

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ApiGuard implements Guard
{
    /**
     * The identity the user authenticated with.
     *
     * @var \App\Models\Identity
     */
    protected $identity;

    /**
     * The authenticated user instance.
     *
     * @var \App\Models\User
     */
    protected $user;

    /**
     * Create a new API guard.
     *
     * @param  int|null  $ttl  How long the auth token will be valid for.
     * @param  string|null  $ttl_type  The unit of time used by the ttl.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determines if the request contains a Bearer token in the HTTP headers.
     *
     * @param  \Illuminate\Http\Request|null  $request
     *
     * @return bool
     */
    public function hasToken(Request|null $request = null): bool
    {
        return (bool) ($request ?? request())->bearerToken();
    }

    /**
     * Retrieve the authenticated identity for the incoming request.
     *
     * @param  \Illuminate\Http\Request|null  $request
     *
     * @return \App\Models\Identity
     *
     * @throws \App\Exceptions\Api\v1\Auth\MissingToken
     * @throws \App\Exceptions\Api\v1\Auth\InvalidToken
     */
    public function parseToken(Request|null $request = null): Identity|null
    {
        if ($this->identity) {
            return $this->identity;
        }

        $request = $request ?? request();
        $bearer = $request->bearerToken();

        if (is_null($bearer)) {
            throw new MissingToken;
        }

        $now = now();
        $token = Token::findFromBearer($bearer);

        if (is_null($token)) {
            throw new InvalidToken;
        } else if ($token->expires_at && $now->gte($token->expires_at)) {
            throw new ExpiredToken;
        }

        $token->forceFill(['last_used_at' => $now])->save();

        $this->identity = $token->tokenable;
        $this->user = User::find($this->identity->user_id);
        $this->user->forceFill(['last_active_at' => $now])->save();

        return $this->identity;
    }

    /**
     * Attempt to authenticate the user with the given login credentials.
     *
     * @param  array  $fields  The fields containing the raw credentials data, typically from a request.
     *
     * @return \App\Models\Identity
     */
    public function attempt(array $fields = []): Identity
    {
        $credentials = Credentials::fromFields($fields);

        if (is_null($credentials->secret)) {
            throw new MissingSecret;
        }

        if ($credentials->identity->user->is_disabled) {
            throw new UserDisabled;
        }

        call_user_func(
            [$this, "bySecret{$credentials->secret->type->value}"],
            $fields,
            $credentials,
        );

        AuthAttempted::dispatch($credentials->identity, true);

        $this->identity = $credentials->identity;
        $this->user = User::find($this->identity->user_id);
        $this->user->forceFill(['last_active_at' => now()])->save();

        return $this->identity;
    }

    /**
     * Validate a user's credentials.
     *
     * @param  array  $fields  The fields containing the raw credentials data, typically from a request.
     *
     * @return bool
     */
    public function validate(array $fields = []): bool
    {
        return (bool) $this->attempt($fields);
    }

    /**
     * @param  array  $fields  The fields containing the raw credentials data, typically from a request.
     * @param  Credentials  $credentials  Instances of the models referred to by the raw credentials data.
     *
     * @return void
     *
     * @throws \App\Exceptions\Api\v1\Auth\NotOAuthIdentity
     * @throws \App\Exceptions\Api\v1\Auth\MissingOAuthProvider
     */
    protected function bySecretOAuth(array $fields, Credentials $credentials): void
    {
        if (! $credentials->identity->is_oauth) {
            throw new NotOAuthIdentity;
        }

        if (! $credentials->identity->provider) {
            throw new MissingOAuthProvider;
        }

        OAuthDriver::get($credentials->identity->provider)
                   ->stateless()
                   ->user();
    }

    /**
     * Attempts to validate the fields data against the known password.
     *
     * @param  array  $fields  The fields containing the raw credentials data, typically from a request.
     * @param  Credentials  $credentials  Instances of the models referred to by the raw credentials data.
     *
     * @return void
     *
     * @throws \App\Exceptions\Api\v1\Auth\InvalidCredentials
     */
    protected function bySecretPassword(array $fields, Credentials $credentials): void
    {
        if (!Hash::check($fields['secret']['value'], $credentials->secret->value)) {
            AuthAttempted::dispatch($credentials->identity, false);
            throw new InvalidCredentials;
        }
    }

    /**
     * Attempts to validate the fields data against the TOTP.
     *
     * @param  array  $fields  The fields containing the raw credentials data, typically from a request.
     * @param  Credentials  $credentials  Instances of the models referred to by the raw credentials data.
     *
     * @return void
     */
    protected function bySecretTotp(array $fields, Credentials $credentials): void
    {
        //
    }

    /**
     * Determine if the current user is authenticated.
     *
     * @return bool
     */
    public function check(): bool
    {
        return (bool) $this->identity;
    }

    /**
     * Determine if the current user is a guest.
     *
     * @return bool
     */
    public function guest(): bool
    {
        return is_null($this->identity);
    }

    /**
     * Get the currently authenticated user.
     *
     * @return \App\Models\User|null
     */
    public function user(): User|null
    {
        return $this->user;
    }

    /**
     * Get the identity used by the currently authenticated user.
     *
     * @return \App\Models\Identity|null
     */
    public function identity(): Identity|null
    {
        return $this->identity ?? null;
    }

    /**
     * Determine if the guard has a user instance.
     *
     * @return bool
     */
    public function hasUser(): bool
    {
        return (bool) $this->user;
    }

    /**
     * Get the id of the currently authenticated user.
     *
     * @return int|null
     */
    public function id(): int|null
    {
        return $this->user?->getAuthIdentifier() ?? null;
    }

    /**
     * This function isn't used and is implemented to satisfy the Guard interface.
     *
     * @param  \App\Models\User  $user  The instance representing the currently authenticated user.
     *
     * @return void
     */
    public function setUser($user): void
    {
        // Intentionally left blank.
    }

    /**
     * Retrieve an instance of this ApiGuard. Intended to help avoid false
     * positive errors with linters.
     *
     * This is an alias of the ApiGuard::getInstance() function.
     *
     * @return \App\Guards\Api\v1\ApiGuard
     */
    static public function get(): ApiGuard
    {
        return self::getInstance();
    }

    /**
     * Retrieve an instance of this ApiGuard. Intended to help avoid false
     * positive errors with linters.
     *
     * @return \App\Guards\Api\v1\ApiGuard
     */
    static public function getInstance(): ApiGuard
    {
        return auth(config('api.guard.name'));
    }
}
