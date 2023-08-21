<?php

namespace App\Enums\Account;

enum AccountStatus: string
{
    case Ok = 'Ok';
    case Suspended = 'Suspended';
}
