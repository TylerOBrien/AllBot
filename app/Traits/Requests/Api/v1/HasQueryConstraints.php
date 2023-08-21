<?php

namespace App\Traits\Requests\Api\v1;

trait HasQueryConstraints
{
    /**
     * Get the store validation rules for an index request.
     *
     * @param  string|null  $model_name_override
     *
     * @return array<string, string>
     */
    protected function indexRules(string|null $model_name_override = null): array
    {
        return [
            'filter' => 'query_filter:' . ($model_name_override ?? $this->model),
            'sort' => 'query_sort:' . ($model_name_override ?? $this->model),
            'limit' => 'int|between:1,' . config('queries.limit.max'),
            'search' => 'sometimes|array',
            'search.query' => 'string',
        ];
    }
}
