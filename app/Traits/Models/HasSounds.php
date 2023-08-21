<?php

namespace App\Traits\Models;

use App\Models\Sound;

use Illuminate\Http\UploadedFile;

trait HasSounds
{
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function sound()
    {
        return $this->morphOne(Sound::class, 'owner');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function sounds()
    {
        return $this->morphMany(Sound::class, 'owner');
    }

    /*
    |--------------------------------------------------------------------------
    | Utilities
    |--------------------------------------------------------------------------
    */

    /**
     * Create a new sound instance for this model.
     *
     * @param  string  $name  The name of the sound.
     * @param  \Illuminate\Http\UploadedFile  $sound  The uploaded sound file.
     * @param  array  $fields  The attributes to apply to the sound.
     *
     * @return \App\Models\Sound
     */
    public function createSound(UploadedFile $sound, array $fields = []): Sound
    {
        return Sound::createFromFile($sound, array_merge($fields, [
            'owner_id' => $this->id,
            'owner_type' => self::class,
        ]));
    }

    /**
     * Update a sound with the given name or create a new sound instance if one
     * matching the given name does not exist.
     *
     * @param  array<string, mixed>  $existing
     * @param  \Illuminate\Http\UploadedFile  $sound  The uploaded sound file.
     * @param  array<string, mixed>  $attributes  The attributes to apply to the sound.
     *
     * @return \App\Models\Sound
     */
    public function updateOrCreateSound(array $existing, UploadedFile $sound, array $attributes = []): Sound
    {
        $merged = array_merge($existing, $attributes);
        $model = $this->sound()
                      ->where('name', $existing['name'])
                      ->first();

        if (is_null($model)) {
            $model = $this->createSound($sound, $merged);
        } else {
            $model->updateFromFile($sound, $merged);
        }

        return $model;
    }
}
