<?php

namespace App\Http\Requests\Api\v1;

use App\Exceptions\Api\v1\Auth\Forbidden;

use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Validation\{ Factory as ValidationFactory, Validator, ValidatesWhenResolved };
use Illuminate\Http\Request;
use Illuminate\Validation\ValidatesWhenResolvedTrait;

class ApiRequest extends Request implements ValidatesWhenResolved
{
    use ValidatesWhenResolvedTrait;

    /**
     * The instance of the current ApiRequest.
     *
     * @var \App\Http\Requests\Api\v1\ApiRequest
     */
    static protected $current;

    /**
     * The name of the ability (e.g. index, store, destroy) the request is using.
     *
     * @var string
     */
    protected $ability;

    /**
     * The name of the bound param.
     *
     * @var string
     */
    protected $binding;

    /**
     * The fully qualified class name of the model.
     *
     * @var string
     */
    protected $model;

    /**
     * The container instance.
     *
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $container;

    /**
     * The validator instance.
     *
     * @var \Illuminate\Contracts\Validation\Validator
     */
    protected $validator;

    /**
     * Determines if this request is intended for admins.
     *
     * @var bool
     */
    protected $isAdminFacing;

    /**
     * Check if the currently authenticated user has been authorized to perform
     * the request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        /** @var \App\Models\User */
        $user = auth()->user();
        $target = $this->binding ? $this->route($this->binding) : $this->model;

        if ($user && $this->ability) {
            return $user->can($this->ability, $target);
        }

        return true; // Either no user or no ability, meaning nothing is being done, so we can authorize.
    }

    /**
     * Determines if this request is intended for admins.
     *
     * @return bool
     */
    public function isAdminFacing(): bool
    {
        if (is_null(self::$isAdminFacing)) {
            if (app()->hasDebugModeEnabled() && $this->hasHeader(config('foo.admin.header'))) {
                $header = $this->header(config('foo.admin.header'));
                $header = ($header === 'false') ? false : $header;

                self::$isAdminFacing = (bool) $header;
            } else {
                if ($origin = $this->header('Origin')) {
                    self::$isAdminFacing = (bool) preg_match(config('foo.admin.regex'), $origin);
                } else {
                    self::$isAdminFacing = false;
                }
            }
        }

        return self::$isAdminFacing;
    }

    /**
     * Determines if this request is intended for users.
     *
     * @return bool
     */
    public function isUserFacing(): bool
    {
        return ! $this->isAdminFacing();
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, mixed>
     */
    public function attributes(): array
    {
        return [];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [];
    }

    /**
     * Get the default values for fields that have not been provided.
     *
     * @return array<string, mixed>
     */
    public function defaults(): array
    {
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    public function transform(): array
    {
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    public function translate(): array
    {
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function validated(): array
    {
        $fields = $this->validator->validated();
        $defaults = $this->defaults() ?? [];
        $translations = $this->translate() ?? [];
        $transformations = $this->transform() ?? [];

        foreach ($defaults as $key => $value) {
            if (! isset($fields[$key])) {
                $fields[$key] = $value;
            }
        }

        $translated = $fields;

        foreach ($translations as $from => $to) {
            if (isset($translated[$from])) {
                $translated[$to] = $fields[$from];
                unset($translated[$from]);
            }
        }

        foreach ($transformations as $key => $value) {
            if (isset($translated[$key])) {
                $translated[$key] = $value;
            }
        }

        return $translated;
    }

    /**
     * @return array
     */
    public function validationData(): array
    {
        return $this->all();
    }

    /**
     * Handle the failed request authorization.
     *
     * @return void
     *
     * @throws \App\Exceptions\Api\v1\Auth\Forbidden
     */
    protected function failedAuthorization()
    {
        throw new Forbidden(
            $this->ability,
            $this->binding ? $this->route($this->binding) : $this->model,
        );
    }

    /**
     * Create the default validator instance.
     *
     * @param  \Illuminate\Contracts\Validation\Factory  $factory
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function createDefaultValidator(ValidationFactory $factory)
    {
        return $factory->make(
            $this->validationData(),
            $this->container->call([$this, 'rules']),
            $this->messages(),
            $this->attributes(),
        );
    }

    /**
     * Get the current ApiRequest instance.
     *
     * @return \App\Http\Requests\Api\v1\ApiRequest
     */
    static public function getCurrent(): ApiRequest
    {
        return ApiRequest::$current;
    }

    /**
     * Get the validator instance for the request.
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function getValidatorInstance()
    {
        if ($this->validator) {
            return $this->validator;
        }

        $factory = $this->container->make(ValidationFactory::class);

        if (method_exists($this, 'validator')) {
            $validator = $this->container->call([$this, 'validator'], compact('factory'));
        } else {
            $validator = $this->createDefaultValidator($factory);
        }

        if (method_exists($this, 'withValidator')) {
            $this->withValidator($validator);
        }

        $this->setValidator($validator);

        return $this->validator;
    }

    /**
     * Set the container implementation.
     *
     * @param  \Illuminate\Contracts\Container\Container  $container
     *
     * @return $this
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;

        return $this;
    }

    /**
     * Set the current ApiRequest instance.
     *
     * @param  \App\Http\Requests\Api\v1  $validator
     *
     * @return void
     */
    static public function setCurrent(ApiRequest $request)
    {
        ApiRequest::$current = $request;
    }

    /**
     * Set the Validator instance.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     *
     * @return $this
     */
    public function setValidator(Validator $validator)
    {
        $this->validator = $validator;

        return $this;
    }
}
