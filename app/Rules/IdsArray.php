<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class IdsArray implements Rule
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
        if (! is_array($value)) {
            return false;
        }

        foreach ($value as $id) {
            if (is_int($id)) {
                if ($id < 1) {
                    return false;
                }
            } else if (is_string($id)) {
                if (! ctype_digit($id) || intval($id) < 1) {
                    return false;
                }
            } else {
                return false;
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.ids-array');
    }
}

