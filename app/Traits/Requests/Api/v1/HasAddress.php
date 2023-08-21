<?php

namespace App\Traits\Requests\Api\v1;

trait HasAddress
{
    /**
     * Get the store validation rules that apply to the request.
     *
     * @return array<string, string>
     */
    protected function addressStoreRules(string $prefix = ''): array
    {
        if ($prefix) {
            $rules = ["{$prefix}" => 'nullable|sometimes|array'];
        } else {
            $rules = [];
        }

        return array_merge($rules, [
            "{$prefix}line1" => 'nullable|string',
            "{$prefix}line2" => 'nullable|string',
            "{$prefix}city" => 'nullable|string',
            "{$prefix}subdivision" => 'nullable|string|size:2',
            "{$prefix}country" => 'nullable|string|size:2',
            "{$prefix}code" => 'nullable|string'
        ]);
    }

    /**
     * Get the store validation rules that apply to the request.
     *
     * @return array<string, string>
     */
    protected function addressUpdateRules(string $prefix = ''): array
    {
        if ($prefix) {
            $rules = ["{$prefix}" => 'nullable|sometimes|array'];
        } else {
            $rules = [];
        }

        return array_merge($rules, [
            "{$prefix}line1" => 'nullable|string',
            "{$prefix}line2" => 'nullable|string',
            "{$prefix}city" => 'nullable|string',
            "{$prefix}subdivision" => 'nullable|string|size:2',
            "{$prefix}country" => 'nullable|string|size:2',
            "{$prefix}code" => 'nullable|string'
        ]);
    }
}
