<?php

namespace App\Rules;

use Exception;

use L91\ISO_3166_2\Subdivision as L91Subdivision;
use League\ISO3166\ISO3166;

use Illuminate\Contracts\Validation\Rule;

class Subdivision implements Rule
{
    /**
     * The country code this state/province is expected to belong to.
     *
     * @var string
     */
    protected $country_code;

    /**
     * Create a new rule.
     *
     * @param  array  $parameters
     *
     * @return bool
     */
    public function __construct(array $parameters)
    {
        $this->country_code = strtoupper($parameters[0] ?? '');
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        try {
            $countries = new ISO3166;
            $alpha2 = $countries->alpha3($this->country_code)['alpha2'];
        } catch (Exception $error) {
            return false;
        }

        $iso31662 = [ strtoupper($alpha2), strtoupper($value) ];

        /** @var stdClass  */
        $l91 = L91Subdivision::getSubdivisions($alpha2);
        $subdivisions = get_object_vars($l91);

        return in_array(implode('-', $iso31662), array_keys($subdivisions));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return trans('validation.address.subdivision');
    }
}
