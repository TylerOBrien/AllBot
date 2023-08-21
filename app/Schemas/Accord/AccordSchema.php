<?php

namespace App\Schemas\Accord;

use App\Exceptions\Api\v1\Schema\InvalidOptions;
use App\Schemas\Schema;

class AccordSchema extends Schema
{
    /**
     * Instantiates this schema.
     *
     * @param  int|null  $options  The options value to use.
     *
     * @return void
     */
    public function __construct(int|null $options = Schema::STORE)
    {
        parent::__construct($options);
    }

    /**
     * @return array<string, string>
     *
     * @throws \App\Exceptions\Api\v1\Credentials\InvalidOptions
     */
    public function rules(): array
    {
        return match($this->options) {
            Schema::STORE => $this->storeRules(),
            Schema::UPDATE => $this->updateRules(),
            default => throw new InvalidOptions,
        };
    }

    /**
     * @return array<string, string>
     */
    public function storeRules(): array
    {
        return [
            'to_id' => 'required|int',
            'to_type' => 'required|string',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function updateRules(): array
    {
        return [
            'to_id' => 'int',
            'to_type' => 'string',
        ];
    }
}
