<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class RequiredIfType implements Rule
{
    /**
     * @var array<string, string>
     */
    protected $checks;

    /**
     * @param  array<int, string>  $parameters
     *
     * @return void
     */
    public function __construct(array $parameters)
    {
        $this->checks = [];

        for ($i = 0; $i < count($parameters); $i += 2) {
            $this->checks[$parameters[$i]] = $parameters[$i + 1];
        }
    }

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
        $request = request();

        foreach ($this->checks as $name => $type) {
            $matches = match ($type) {
                'array' => is_array($request->input($name)),
                'int' => is_int($request->input($name)),
                'null' => is_null($request->input($name)),
                default => false,
            };

            if (! $matches) {
                return true;
            }
        }

        return ! empty($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.required-if-type');
    }
}

