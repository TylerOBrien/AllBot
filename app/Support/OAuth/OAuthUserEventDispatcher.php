<?php

namespace App\Support\OAuth;

use App\Events\Api\v1\OAuth\{
    FacebookUserIdentified,
    GitHubUserIdentified,
    GoogleUserIdentified,
};

use Laravel\Socialite\Two\{
    FacebookProvider,
    GithubProvider,
    GoogleProvider,
};

class OAuthUserEventDispatcher
{
    /**
     * @param  FacebookProvider|GoogleProvider|GithubProvider  $driver  The Socialite provider.
     *
     * @return void
     */
    static public function dispatch(FacebookProvider|GoogleProvider|GithubProvider $driver): void
    {
        if ($driver instanceof FacebookProvider) {
            FacebookUserIdentified::dispatch($driver);
        } else if ($driver instanceof GoogleProvider) {
            GoogleUserIdentified::dispatch($driver);
        } else if ($driver instanceof GithubProvider) {
            GitHubUserIdentified::dispatch($driver);
        }
    }
}
