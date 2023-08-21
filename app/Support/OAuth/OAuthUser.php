<?php

namespace App\Support\OAuth;

use App\Enums\OAuth\OAuthProvider;
use App\Models\{ AppleUser, FacebookUser, GitHubUser, GoogleUser, TwitterUser, Identity };

class OAuthUser
{
    /**
     * Create a new instance of the appropriate OAuth user model based on the
     * OAuth provider for the given identity.
     *
     * @param  \App\Models\Identity  $identity  The OAuth-based identity instance.
     *
     * @return \App\Models\AppleUser|FacebookUser|GitHubUser|GoogleUser|TwitterUser
     */
    static public function create(Identity $identity): AppleUser|FacebookUser|GitHubUser|GoogleUser|TwitterUser
    {
        $fields = OAuthDriver::user($identity);

        return match ($identity->provider) {
            OAuthProvider::Apple => self::createApple($identity, $fields),
            OAuthProvider::Facebook => self::createFacebook($identity, $fields),
            OAuthProvider::GitHub => self::createGitHub($identity, $fields),
            OAuthProvider::Google => self::createGoogle($identity, $fields),
            OAuthProvider::Twitter => self::createTwitter($identity, $fields),
        };
    }

    /**
     * Create a new AppleUser instance.
     *
     * @param  \App\Models\Identity  $identity  The OAuth-based identity instance.
     * @param  \Laravel\Socialite\Two\User  $fields  The Socialite user data instance.
     *
     * @return \App\Models\AppleUser
     */
    static public function createApple(Identity $identity, \Laravel\Socialite\Two\User $fields): AppleUser
    {
        return AppleUser::create([
            'identity_id' => $identity->id,
            'apple_id' => $fields->id,
            'email' => $fields->email,
        ]);
    }

    /**
     * Create a new FacebookUser instance.
     *
     * @param  \App\Models\Identity  $identity  The OAuth-based identity instance.
     * @param  \Laravel\Socialite\Two\User  $fields  The Socialite user data instance.
     *
     * @return \App\Models\FacebookUser
     */
    static public function createFacebook(Identity $identity, \Laravel\Socialite\Two\User $fields): FacebookUser
    {
        return FacebookUser::create([
            'identity_id' => $identity->id,
            'facebook_id' => $fields->id,
            'email' => $fields->email,
            'name' => $fields->name,
            'profile_image_url' => $fields->avatar,
        ]);
    }

    /**
     * Create a new GitHubUser instance.
     *
     * @param  \App\Models\Identity  $identity  The OAuth-based identity instance.
     * @param  \Laravel\Socialite\Two\User  $fields  The Socialite user data instance.
     *
     * @return \App\Models\GitHubUser
     */
    static public function createGitHub(Identity $identity, \Laravel\Socialite\Two\User $fields): GitHubUser
    {
        return GitHubUser::create([
            'identity_id' => $identity->id,
            'github_id' => $fields->id,
            'email' => $fields->email,
            'nickname' => $fields->nickname,
            'profile_image_url' => $fields->avatar,
        ]);
    }

    /**
     * Create a new GoogleUser instance.
     *
     * @param  \App\Models\Identity  $identity  The OAuth-based identity instance.
     * @param  \Laravel\Socialite\Two\User  $fields  The Socialite user data instance.
     *
     * @return \App\Models\GoogleUser
     */
    static public function createGoogle(Identity $identity, \Laravel\Socialite\Two\User $fields): GoogleUser
    {
        return GoogleUser::create([
            'identity_id' => $identity->id,
            'google_id' => $fields->id,
            'email' => $fields->email,
            'given_name' => $fields->user['given_name'],
            'family_name' => $fields->user['family_name'],
            'profile_image_url' => $fields->avatar,
        ]);
    }

    /**
     * Create a new TwitterUser instance.
     *
     * @param  \App\Models\Identity  $identity  The OAuth-based identity instance.
     * @param  \Laravel\Socialite\Two\User  $fields  The Socialite user data instance.
     *
     * @return \App\Models\TwitterUser
     */
    static public function createTwitter(Identity $identity, \Laravel\Socialite\Two\User $fields): TwitterUser
    {
        return TwitterUser::create([
            'identity_id' => $identity->id,
            'twitter_id' => $fields->id,
            'email' => $fields->email,
            'name' => $fields->name,
            'nickname' => $fields->nickname,
            'profile_image_url' => $fields->avatar,
        ]);
    }
}
