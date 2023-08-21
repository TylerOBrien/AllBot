<?php

namespace App\Support\OAuth;

use SocialiteProviders\Apple\Provider as AppleProvider;

use Laravel\Socialite\Two\{ FacebookProvider, GithubProvider, GoogleProvider, TwitterProvider };

class OAuthProvider
{
    /**
     * @var array<string, \App\Enums\OAuth\OAuthProvider>
     */
    static protected $enums = [
        AppleProvider::class => \App\Enums\OAuth\OAuthProvider::Apple,
        FacebookProvider::class => \App\Enums\OAuth\OAuthProvider::Facebook,
        GithubProvider::class => \App\Enums\OAuth\OAuthProvider::GitHub,
        GoogleProvider::class => \App\Enums\OAuth\OAuthProvider::Google,
        TwitterProvider::class => \App\Enums\OAuth\OAuthProvider::Twitter,
    ];

    /**
     * Generate an array containing properties for all OAuth providers.
     *
     * @return array
     */
    static public function all(): array
    {
        $providers = [];

        foreach (self::$enums as $class => $enum) {
            $providers[] = [
                'name' => $enum->value,
            ];
        }

        return $providers;
    }

    /**
     * Attempts to retrieve the string-based name of the given OAuth provider.
     * Will return null if the appropriate name cannot be found.
     *
     * @param  \SocialiteProviders\Apple\Provider|\Laravel\Socialite\Two\FacebookProvider|GithubProvider|GoogleProvider|TwitterProvider  $driver  The driver to get the name of.
     *
     * @return string|null
     */
    static public function name(AppleProvider|FacebookProvider|GithubProvider|GoogleProvider|TwitterProvider $driver): string|null
    {
        return self::enum($driver)?->value;
    }

    /**
     * Attempts to retrieve the PHP enum for the given OAuth provider.
     * Will return null if the appropriate name cannot be found.
     *
     * @param  \SocialiteProviders\Apple\Provider|\Laravel\Socialite\Two\FacebookProvider|GithubProvider|GoogleProvider|TwitterProvider  $driver  The driver to get the name of.
     *
     * @return \App\Enums\OAuth\OAuthProvider|null
     */
    static public function enum(AppleProvider|FacebookProvider|GithubProvider|GoogleProvider|TwitterProvider $driver): \App\Enums\OAuth\OAuthProvider|null
    {
        foreach (self::$enums as $class => $enum) {
            if ($driver instanceof $class) {
                return $enum;
            }
        }

        return null;
    }
}
