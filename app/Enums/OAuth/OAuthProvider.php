<?php

namespace App\Enums\OAuth;

enum OAuthProvider: string
{
    case Apple = 'Apple';
    case Facebook = 'Facebook';
    case GitHub = 'GitHub';
    case Google = 'Google';
    case Twitch = 'Twitch';
    case Twitter = 'Twitter';
}
