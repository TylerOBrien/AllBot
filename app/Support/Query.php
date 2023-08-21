<?php

namespace App\Support;

use App\Enums\Query\{ QueryConstraint, QueryOperator };

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\{ BelongsToMany, HasMany };
use Illuminate\Support\Str;;

class Query
{
    /**
     * The supported constraints that will be used.
     *
     * @var array<\App\Enums\Query\QueryConstraint>
     */
    static protected $constraints = [
        QueryConstraint::IS,
        QueryConstraint::ISNT,
        QueryConstraint::IS_NULL,
        QueryConstraint::ISNT_NULL,
        QueryConstraint::LESS,
        QueryConstraint::GREATER,
        QueryConstraint::MIN,
        QueryConstraint::MAX,
        QueryConstraint::PREFIX,
        QueryConstraint::SUFFIX,
        QueryConstraint::HAS,
        QueryConstraint::HASNT,
    ];

    /**
     * Apply the given filter to the query builder then return the builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query  The Eloquent query to apply the constraints to.
     * @param  array<string, mixed>  $filter  The column and constraint definitions.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    static public function filter(Builder|BelongsToMany|HasMany $query, array $filter): Builder|BelongsToMany|HasMany
    {
        foreach ($filter as $column => $given_constraints) {
            $query = self::process($query, $column, $given_constraints);
        }

        return $query;
    }

    /**
     * Determine the appropraite constraint to apply to the query based on the
     * given column and constraint values.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query  The Eloquent query to apply the constraints to.
     * @param  string  $column  The name of the column to constrain.
     * @param  array<string, mixed>  $given_constraints  The names and values of the constraints.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    static protected function process(Builder|BelongsToMany|HasMany $query, string $column, array $given_constraints): Builder|BelongsToMany|HasMany
    {
        $given_constraint_names = array_keys($given_constraints);

        if (empty($given_constraint_names)) {
            return $query;
        }

        $constraints_to_apply = []; // The constraints that will be applied to the query.

        // Before looping over allowed constraints check for both MIN and MAX
        // which implicitly means BETWEEN. If so also remove the MIN and MAX
        // keys so they are not handled a second time in the loop below.

        if (in_array(QueryConstraint::MIN->value, $given_constraint_names)) {
            if (in_array(QueryConstraint::MAX->value, $given_constraint_names)) {
                $constraints_to_apply[] = ['between', [ $given_constraints['min'], $given_constraints['max'] ]];
                unset($given_constraint_names[array_search(QueryConstraint::MIN->value, $given_constraint_names)]);
                unset($given_constraint_names[array_search(QueryConstraint::MAX->value, $given_constraint_names)]);
            }
        }

        foreach (self::$constraints as $allowed_constraint) {
            if (in_array($allowed_constraint->value, $given_constraint_names)) {
                $constraints_to_apply[] = [$allowed_constraint->value, $given_constraints[$allowed_constraint->value]];
            }
        }

        foreach ($constraints_to_apply as [ $name, $value ]) {
            $query = self::$name($query, $column, $value);
        }

        return $query;
    }

    /**
     * Apply the IS constraint to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query  The Eloquent query to apply the constraints to.
     * @param  string  $column  The name of the column to constrain.
     * @param  mixed  $value  The value to constrain to.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    static protected function is(Builder|BelongsToMany|HasMany $query, string $column, $value): Builder|BelongsToMany|HasMany
    {
        return $query->where($column, '=', $value);
    }

    /**
     * Apply the ISNT constraint to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query  The Eloquent query to apply the constraints to.
     * @param  string  $column  The name of the column to constrain.
     * @param  mixed  $value  The value to constrain to.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    static protected function isnt(Builder|BelongsToMany|HasMany $query, string $column, $value): Builder|BelongsToMany|HasMany
    {
        return $query->where($column, '!=', $value);
    }

    /**
     * Apply the IS_NULL constraint to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query  The Eloquent query to apply the constraints to.
     * @param  string  $column  The name of the column to constrain.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    static protected function is_null(Builder|BelongsToMany|HasMany $query, string $column): Builder|BelongsToMany|HasMany
    {
        return $query->whereNull($column);
    }

    /**
     * Apply the LESS constraint to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query  The Eloquent query to apply the constraints to.
     * @param  string  $column  The name of the column to constrain.
     * @param  mixed  $value  The value to constrain to.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    static protected function less(Builder|BelongsToMany|HasMany $query, string $column, $value): Builder|BelongsToMany|HasMany
    {
        return $query->where($column, '<', $value);
    }

    /**
     * Apply the GREATER constraint to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query  The Eloquent query to apply the constraints to.
     * @param  string  $column  The name of the column to constrain.
     * @param  mixed  $value  The value to constrain to.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    static protected function greater(Builder|BelongsToMany|HasMany $query, string $column, $value): Builder|BelongsToMany|HasMany
    {
        return $query->where($column, '>', $value);
    }

    /**
     * Apply the MIN constraint to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query  The Eloquent query to apply the constraints to.
     * @param  string  $column  The name of the column to constrain.
     * @param  mixed  $value  The value to constrain to.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    static protected function min(Builder|BelongsToMany|HasMany $query, string $column, $value): Builder|BelongsToMany|HasMany
    {
        return $query->where($column, '>=', $value);
    }

    /**
     * Apply the MAX constraint to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query  The Eloquent query to apply the constraints to.
     * @param  string  $column  The name of the column to constrain.
     * @param  mixed  $value  The value to constrain to.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    static protected function max(Builder|BelongsToMany|HasMany $query, string $column, $value): Builder|BelongsToMany|HasMany
    {
        return $query->where($column, '<=', $value);
    }

    /**
     * Apply the MAX constraint to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query  The Eloquent query to apply the constraints to.
     * @param  string  $column  The name of the column to constrain.
     * @param  array<mixed>  $range  The value to constrain to.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    static protected function between(Builder|BelongsToMany|HasMany $query, string $column, array $range): Builder|BelongsToMany|HasMany
    {
        return $query->whereBetween($column, $range);
    }

    /**
     * Apply the PREFIX constraint to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query  The Eloquent query to apply the constraints to.
     * @param  string  $column  The name of the column to constrain.
     * @param  mixed  $value  The value to constrain to.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    static protected function prefix(Builder|BelongsToMany|HasMany $query, string $column, $value): Builder|BelongsToMany|HasMany
    {
        return $query->where($column, 'LIKE', str_replace('%', '\\%', $value) . '%');
    }

    /**
     * Apply the SUFFIX constraint to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query  The Eloquent query to apply the constraints to.
     * @param  string  $column  The name of the column to constrain.
     * @param  mixed  $value  The value to constrain to.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    static protected function suffix(Builder|BelongsToMany|HasMany $query, string $column, $value): Builder|BelongsToMany|HasMany
    {
        return $query->where($column, 'LIKE', '%' . str_replace('%', '\\%', $value));
    }

    /**
     * Apply the HAS constraint to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query  The Eloquent query to apply the constraints to.
     * @param  string  $column  The name of the column to constrain.
     * @param  mixed  $value  The value to constrain to.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    static protected function has(Builder|BelongsToMany|HasMany $query, string $relation, $value = null): Builder|BelongsToMany|HasMany
    {
        if (is_null($value)) {
            return $query->whereHas($relation);
        }

        $operators = [];

        foreach ($value as $column => $constraint) {
            if (is_array($constraint)) {
                $keys = array_keys($constraint);

                if (count($keys) === 1 && ! is_numeric($keys[0])) {
                    $operators[$column] = match ($keys[0]) {
                        'in' => QueryOperator::OR,
                        'is' => QueryOperator::AND,
                    };
                } else {
                    $operators[$column] = QueryOperator::AND;
                }
            } else {
                $operators[$column] = QueryOperator::AND;
            }
        }

        return $query->whereHas($relation, function ($query) use ($operators, $relation, $value) {
            foreach ($value as $column => $constraint) {
                $needle = is_array($constraint) ? $constraint : [ $constraint ];

                switch ($operators[$column]) {
                case QueryOperator::AND:
                    foreach ($needle as $itr) {
                        $query = $query->where(Str::plural($relation) . ".$column", $itr);
                    }
                    break;
                case QueryOperator::IN:
                case QueryOperator::OR:
                    $query = $query->whereIn(Str::plural($relation) . ".$column", $needle['in']);
                    break;
                }
            }

            return $query;
        });
    }

    /**
     * Apply the HASNT constraint to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query  The Eloquent query to apply the constraints to.
     * @param  string  $column  The name of the column to constrain.
     * @param  mixed  $value  The value to constrain to.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    static protected function hasnt(Builder|BelongsToMany|HasMany $query, string $relation, $value = null): Builder|BelongsToMany|HasMany
    {
        if (is_null($value)) {
            return $query->whereDoesntHave($relation);
        }

        $operators = [];

        foreach ($value as $column => $constraint) {
            if (is_array($constraint)) {
                $keys = array_keys($constraint);

                if (count($keys) === 1 && ! is_numeric($keys[0])) {
                    $operators[$column] = match ($keys[0]) {
                        'in' => QueryOperator::OR,
                        'is' => QueryOperator::AND,
                    };
                } else {
                    $operators[$column] = QueryOperator::AND;
                }
            } else {
                $operators[$column] = QueryOperator::AND;
            }
        }

        return $query->whereDoesntHave($relation, function ($query) use ($operators, $relation, $value) {
            foreach ($value as $column => $constraint) {
                $needle = is_array($constraint) ? $constraint : [ $constraint ];

                switch ($operators[$column]) {
                case QueryOperator::AND:
                    foreach ($needle as $itr) {
                        $query = $query->where(Str::plural($relation) . ".$column", $itr);
                    }
                    break;
                case QueryOperator::IN:
                case QueryOperator::OR:
                    $query = $query->whereIn(Str::plural($relation) . ".$column", $needle['in']);
                    break;
                }
            }

            return $query;
        });
    }
}
