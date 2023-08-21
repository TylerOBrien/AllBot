<?php

namespace App\Traits\Models;

use App\Models\Profile;

use Illuminate\Database\Eloquent\Collection;

trait HasProfiles
{
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function profile()
    {
        return $this->morphOne(Profile::class, 'owner');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function profiles()
    {
        return $this->morphMany(Profile::class, 'owner');
    }

    /*
    |--------------------------------------------------------------------------
    | Utilities
    |--------------------------------------------------------------------------
    */

    /**
     * Create new profile fields for this model.
     *
     * @param  array<string, mixed>  $existing  The potentially existing attributes.
     * @param  array<string, mixed>  $attributes  The ProfileField data to insert into storage.
     *
     * @return \Illuminate\Database\Eloquent\Collection<\App\Models\ProfileField>
     */
    public function createProfileFields(array $existing, array $attributes): Collection
    {
        return $this->profile()
                    ->firstOrCreate($existing)
                    ->fields()
                    ->createMany($attributes);
    }

    /**
     * @param  string  $name  The name of the profile.
     * @param  array  $fields  The ProfileField data to insert into storage.
     *
     * @return void
     */
    public function updateOrCreateProfileFields(array $existing, array $fields)
    {
        $profile = $this->profile()->firstOrCreate($existing);
        $deleting = [];

        foreach ($fields as $field) {
            if ($field['value']) {
                $profile->fields()->updateOrCreate(['name' => $field['name']], $field);
            } else {
                $deleting[] = $field['name'];
            }
        }

        if ($deleting) {
            $profile->fields()
                    ->where(function ($query) use ($deleting) {
                        foreach ($deleting as $name) {
                            $query = $query->orWhere('name', $name);
                        }

                        return $query;
                    })
                    ->delete();
        }
    }
}
