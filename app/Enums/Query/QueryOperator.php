<?php

namespace App\Enums\Query;

enum QueryOperator: string
{
    case AND = 'AND';
    case IN  = 'IN';
    case OR  = 'OR';
}
