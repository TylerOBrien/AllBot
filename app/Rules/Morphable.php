<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Str;

class Morphable implements Rule
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
        [ $column, $name ] = explode('_', strrev($attribute), 2);

        $method = 'passes' . strrev($column);
        $validator = [$this, $method];

        if (! method_exists($this, $method)) {
            return false;
        }

        return call_user_func($validator, strrev($name), $value);
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  mixed  $value
     *
     * @return bool
     */
    protected function passesId($name, $value)
    {
        $model = Str::start(request("{$name}_type"), config('models.namespace'));

        if (!class_exists($model)) {
            return false;
        }

        return $model::whereId($value)->exists();
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  mixed  $value
     *
     * @return bool
     */
    protected function passesType($name, $value)
    {
        return class_exists(Str::start($value, config('models.namespace')));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.morphable');
    }
}
