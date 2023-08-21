<?php

namespace App\Enums\Token;

enum TokenAlgorithm: string
{
    case AES = 'AES';
    case SHA256 = 'SHA256';
    case SHA384 = 'SHA384';
    case SHA512 = 'SHA512';
}
