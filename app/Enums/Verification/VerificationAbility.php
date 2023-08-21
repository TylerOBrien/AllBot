<?php

namespace App\Enums\Verification;

enum VerificationAbility: string
{
    case Store = 'store';
    case Recover = 'recover';
    case Update = 'update';
    case Destroy = 'destroy';
}
