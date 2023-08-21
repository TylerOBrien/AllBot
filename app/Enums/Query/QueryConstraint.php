<?php

namespace App\Enums\Query;

enum QueryConstraint: string
{
    case IS        = 'is';
    case ISNT      = 'isnt';
    case IS_NULL   = 'is_null';
    case ISNT_NULL = 'isnt_null';
    case LESS      = 'less';
    case GREATER   = 'greater';
    case MIN       = 'min';
    case MAX       = 'max';
    case PREFIX    = 'prefix';
    case SUFFIX    = 'suffix';
    case HAS       = 'has';
    case HASNT     = 'hasnt';
}
