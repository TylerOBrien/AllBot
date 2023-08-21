<?php

namespace App\Traits\Models;

use App\Models\File;

use Illuminate\Http\UploadedFile;

trait HasFiles
{
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function file()
    {
        return $this->morphOne(File::class, 'owner');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function files()
    {
        return $this->morphMany(File::class, 'owner');
    }

    /*
    |--------------------------------------------------------------------------
    | Utilities
    |--------------------------------------------------------------------------
    */

    /**
     * Create a new file instance for this model.
     *
     * @param  \Illuminate\Http\UploadedFile  $file  The uploaded file.
     * @param  array<string, mixed>  $attributes  The attributes to apply to the file.
     *
     * @return \App\Models\File
     */
    public function createFromFile(UploadedFile $file, array $attributes = []): File
    {
        return File::createFromFile($file, array_merge($attributes, [
            'owner_id' => $this->id,
            'owner_type' => self::class,
        ]));
    }

    /**
     * Update a file with the given name or create a new file instance if one
     * matching the given name does not exist.
     *
     * @param  array<string, mixed>  $existing
     * @param  \Illuminate\Http\UploadedFile  $file  The uploaded video file.
     * @param  array<string, mixed>  $attributes  The attributes to apply to the video.
     *
     * @return \App\Models\File
     */
    public function updateOrCreateFile(array $existing, UploadedFile $file, array $attributes = []): File
    {
        $merged = array_merge($existing, $attributes);
        $model = $this->file()
                      ->where('name', $existing['name'])
                      ->first();

        if (is_null($model)) {
            $model = $this->createFromFile($file, $merged);
        } else {
            $model->updateFromFile($file, $merged);
        }

        return $model;
    }
}
