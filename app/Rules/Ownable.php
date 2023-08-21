<?php

namespace App\Rules;

use App\Guards\Api\v1\ApiGuard;
use App\Models\User;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Str;

class Ownable implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $user = ApiGuard::get()->parseToken();

        if (is_null($user)) {
            return false;
        }

        [ $morphable_name, ] = explode('_', $attribute);

        $model_name = Str::start(request("{$morphable_name}_type"), config('models.namespace'));
        $model_id = intval(request("{$morphable_name}_id"));

        if ($model_name === User::class && $model_id === $user->id) {
            return true;
        }

        return $user->can('own', $model_name::find($model_id));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.ownable');
    }
}
