<?php

namespace App\Support\OAuth;

use App\Enums\Identity\IdentityType;
use App\Exceptions\Api\v1\Identity\IdentityIsntOAuth;
use App\Exceptions\Api\v1\OAuth\ProviderNotFound;
use App\Models\Identity;

use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\{ FacebookProvider, GithubProvider, GoogleProvider, TwitterProvider };

use SocialiteProviders\Apple\Provider as AppleProvider;

class OAuthDriver
{
    /**
     * Retrieves the OAuth token for the identity. This is only useable during
     * the initial authentication with the OAuth provider when the user is
     * either registering or logging in.
     *
     * @param  \App\Models\Identity  $identity  The identity to get the token for.
     *
     * @return string
     *
     * @throws \App\Exceptions\Api\v1\OAuth\IdentityIsntOAuth
     */
    static public function token(Identity $identity): string
    {
        if ($identity->type !== IdentityType::OAuth) {
            throw new IdentityIsntOAuth(500);
        }

        return self::user($identity)->token;
    }

    /**
     * Retrieves the Socialite user instance for the given identity. This is
     * only useable during he initial authentication with the OAuth provider
     * when the user is either registering or logging in.
     *
     * @param  \App\Models\Identity  $identity  The identity to get the Socialite user instance for.
     *
     * @return \Laravel\Socialite\Two\User
     *
     * @throws \App\Exceptions\Api\v1\OAuth\IdentityIsntOAuth
     */
    static public function user(Identity $identity): \Laravel\Socialite\Two\User
    {
        if ($identity->type !== IdentityType::OAuth) {
            throw new IdentityIsntOAuth(500);
        }

        return self::get($identity->provider)->stateless()->user();
    }

    /**
     * This is an alias of the Socialite::driver function. It exists to prevent
     * false positive linter errors when calling the stateless() function on
     * the provider returned by Socialite::driver.
     *
     * @param  string  $provider  The name of the OAuth provider.
     *
     * @return \SocialiteProviders\Apple\Provider|\Laravel\Socialite\Two\FacebookProvider|\Laravel\Socialite\Two\GithubProvider|\Laravel\Socialite\Two\GoogleProvider|\Laravel\Socialite\Two\TwitterProvider
     *
     * @throws \App\Exceptions\Api\v1\OAuth\ProviderNotFound
     */
    static public function get(string $provider): AppleProvider|FacebookProvider|GithubProvider|GoogleProvider|TwitterProvider
    {
        if (!in_array($provider, config('enum.oauth.provider'))) {
            throw new ProviderNotFound(500);
        }

        return Socialite::driver($provider);
    }
}
