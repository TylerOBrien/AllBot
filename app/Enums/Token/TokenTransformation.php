<?php

namespace App\Enums\Token;

enum TokenTransformation: string
{
    case Encrypted = 'Encrypted';
    case Hashed = 'Hashed';
}
