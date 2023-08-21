<?php

namespace App\Exceptions\Api\v1\Auth;

use App\Exceptions\Api\ApiException;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Forbidden extends ApiException
{
    /**
     * Create a new exception.
     * 
     * @param  string  $ability
     * @param  string|\Illuminate\Database\Eloquent\Model  $target
     * 
     * @return void
     */
    public function __construct(string $ability, $target)
    {
        if (is_string($target)) {
            $target = '.' . strtolower(Str::afterLast($target, '\\'));
        } else if ($target instanceof Model) {
            $target = '.' . Str::singular($target->getTable()) . '.' . $target->id;
        } else {
            // error
        }

        parent::__construct('auth.forbidden', 403, compact('ability', 'target'));
    }
}
