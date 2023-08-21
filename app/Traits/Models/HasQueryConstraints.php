<?php

namespace App\Traits\Models;

use App\Support\Query;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\{ BelongsToMany, HasMany };

trait HasQueryConstraints
{
    /**
     * Apply any existing constraints to the query.
     *
     * @param  array  $fields  The fields containing the raw constraint data.
     * @param  \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\BelongsToMany|\Illuminate\Database\Eloquent\Relations\HasMany|null  $query  The query to apply filter constraints to.
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\HasMany
     */
    static public function constrained(array $fields, Builder|BelongsToMany|HasMany|null $query = null): Builder|BelongsToMany|HasMany
    {
        $query = $query ?? self::select();

        if (isset($fields['filter'])) {
            $query = Query::filter($query, $fields['filter']);
        }

        if (isset($fields['search']['query'])) {
            $needle = str_replace('%', '', $fields['search']['query']);
            $columns = ($fields['search']['columns'] ?? []) ?: (new self)->searchable ?? [];

            if ($needle && $columns) {
                $query = $query->where(
                    function ($query) use ($needle, $columns) {
                        foreach ($columns as $column) {
                            $query = $query->orWhere($column, 'like', "%{$needle}%");
                        }

                        return $query;
                    }
                );
            }
        }

        return $query;
    }
}
