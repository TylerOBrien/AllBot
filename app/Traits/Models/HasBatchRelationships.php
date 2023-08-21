<?php

namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Relations\{ HasMany, MorphMany };

trait HasBatchRelationships
{
    /*
    |--------------------------------------------------------------------------
    | Utilities
    |--------------------------------------------------------------------------
    */

    /**
     * @return void
     */
    public function dissociateMany(array $fields, string $foreignKey, string $foreignOwnerKey = 'owner_id')
    {
        foreach ($fields as $relationship => $models) {
            $query = call_user_func([ $this, $relationship ]);
            $query = $query->where(function ($query) use ($models) {
                foreach ($models as $attributes) {
                    $query = $query->orWhere('id', $attributes['id']);
                }

                return $query;
            });

            if ($query instanceof HasMany) {
                $query->update([ $foreignKey => null ]);
            } else if ($query instanceof MorphMany) {
                $query->update([ $foreignOwnerKey => null ]);
            }
        }
    }
}
