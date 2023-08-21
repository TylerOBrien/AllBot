<?php

namespace App\Traits\Models;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

trait HasUserAbilities
{
    /**
     * Check if the user instance has the given ability.
     *
     * @param  string  $ability
     * @param  string  $model_type
     * @param  int  $model_id
     *
     * @return bool
     */
    public function hasAbility(string $ability, string $model_type, int $model_id = null)
    {
        return $this->hasTokenAbility($ability, $model_type, $model_id) &&
               $this->hasDatabaseAbility($ability, $model_type, $model_id);
    }

    /**
     * Check if the user instance has the given ability.
     *
     * @param  string  $ability
     * @param  string  $model_type
     * @param  int  $model_id
     *
     * @return bool
     */
    protected function hasTokenAbility(string $ability, string $model_type, int $model_id = null)
    {
        return true;
    }

    /**
     * Check if the user instance has the given ability defined in storage.
     *
     * @param  string  $ability
     * @param  string  $model_type
     * @param  int  $model_id
     *
     * @return bool
     */
    protected function hasDatabaseAbility(string $ability, string $model_type, int $model_id = null)
    {
        $model_type = Str::start($model_type, config('croft.models.namespace'));
        $bindings = array_merge([ 'user_id' => $this->id ], compact('ability', 'model_type'));

        if (is_null($model_id)) {
            $model_id_clause = 'abilities.model_id IS NULL';
        } else {
            $bindings['model_id'] = $model_id;
            $model_id_clause = '(abilities.model_id IS NULL OR abilities.model_id = :model_id)';
        }

        $query = DB::raw("
            SELECT EXISTS(
                SELECT *
                FROM abilities
                JOIN privileges ON privileges.id = abilities.privilege_id
                JOIN privilege_user ON privileges.id = privilege_user.privilege_id
                WHERE privilege_user.user_id = :user_id AND
                    (abilities.name = '*' OR abilities.name = :ability) AND
                    (abilities.model_type = '*' OR abilities.model_type = :model_type) AND
                    $model_id_clause
                LIMIT 1
            ) as `exists`;");

        return (bool) DB::select($query, $bindings)[0]->exists ?? false;
    }
}
