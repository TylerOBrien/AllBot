<?php

namespace App\Enums\Profile;

enum ProfileFieldType: string
{
    case Boolean = 'boolean';
    case Date = 'date';
    case DateTime = 'datetime';
    case Float = 'float';
    case Integer = 'integer';
    case String = 'string';
    case Time = 'time';
}
