<?php

namespace App\Rules;

use Exception;

use League\ISO3166\ISO3166;

use Illuminate\Contracts\Validation\Rule;

class Country implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * 
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (strlen($value) !== 3) {
            return false;
        }

        $iso = new ISO3166;

        try {
            $iso->alpha3($value);
        } catch (Exception $error) {
            return false;
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
        return trans('validation.address.country');
    }
}
