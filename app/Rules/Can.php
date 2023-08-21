<?php

namespace App\Rules;

use App\Guards\Api\v1\ApiGuard;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Str;

class Can implements Rule
{
    /**
     * The name of the ability.
     *
     * @var string
     */
    protected $ability;

    /**
     * The name of the model to check permissions against.
     *
     * @var string
     */
    protected $model_name;

    /**
     * Create a new rule instance.
     *
     * @param  array  $parameters
     *
     * @return void
     */
    public function __construct(array $parameters)
    {
        $this->ability = $parameters[0] ?? null;
        $this->model_name = $parameters[1] ?? null;
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
        /** @var \App\Models\User */
        $user = auth()->user();

        if (is_null($user)) {
            return false;
        }

        $model_class_name = Str::start($this->model_name, config('models.namespace'));
        $instance = $model_class_name::find($value);

        if (is_null($instance)) {
            return true;
        }

        return $user->can($this->ability, $instance);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.can');
    }
}
