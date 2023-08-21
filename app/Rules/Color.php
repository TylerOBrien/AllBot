<?php

namespace App\Rules;

use Exception;

use App\Enums\Color\ColorCode;

use Illuminate\Contracts\Validation\Rule;

class Color implements Rule
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
        if (empty($value) || !is_string($value)) {
            return false;
        }

        $value = strtolower($value);
        $length = strlen($value);

        if ($length !== 7 || $value[0] !== '#') {
            foreach (ColorCode::cases() as $color) {
                if (strtolower($color->name) === $value) {
                    return true;
                }
            }

            return false;
        }

        for ($i = 1; $i < $length; $i++) {
            try {
                if (hexdec($value[$i]) > 15) {
                    return false;
                }
            } catch (Exception $error) {
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
        return trans('validation.color');
    }
}

