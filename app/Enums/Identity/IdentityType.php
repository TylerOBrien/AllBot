<?php

namespace App\Enums\Identity;

enum IdentityType: string
{
    case Email = 'Email';
    case Mobile = 'Mobile';
    case OAuth = 'OAuth';
}
