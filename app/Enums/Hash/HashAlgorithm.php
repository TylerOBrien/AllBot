<?php

namespace App\Enums\Hash;

enum HashAlgorithm: string
{
    case SHA256 = 'SHA256';
    case SHA384 = 'SHA384';
    case SHA512 = 'SHA512';
}
