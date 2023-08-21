<?php

namespace App\Enums\Secret;

enum SecretType: string
{
    case OAuth = 'OAuth';
    case Password = 'Password';
    case TOTP = 'TOTP';
}
