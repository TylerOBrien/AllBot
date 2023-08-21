<?php

namespace App\Traits\Models;

use App\Models\Image;

use Illuminate\Http\UploadedFile;

trait HasImages
{
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function image()
    {
        return $this->morphOne(Image::class, 'owner');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function images()
    {
        return $this->morphMany(Image::class, 'owner');
    }

    /*
    |--------------------------------------------------------------------------
    | Utilities
    |--------------------------------------------------------------------------
    */

    /**
     * Create a new image instance for this model.
     *
     * @param  string  $name  The name of the image.
     * @param  \Illuminate\Http\UploadedFile  $image  The uploaded image file.
     * @param  array  $fields  The attributes to apply to the image.
     *
     * @return \App\Models\Image
     */
    public function createImage(UploadedFile $image, array $fields = []): Image
    {
        return Image::createFromFile($image, array_merge($fields, [
            'owner_id' => $this->id,
            'owner_type' => self::class,
        ]));
    }

    /**
     * Update a image with the given name or create a new image instance if one
     * matching the given name does not exist.
     *
     * @param  array<string, mixed>  $existing
     * @param  \Illuminate\Http\UploadedFile  $image  The uploaded image file.
     * @param  array<string, mixed>  $attributes  The attributes to apply to the image.
     *
     * @return \App\Models\Image
     */
    public function updateOrCreateImage(array $existing, UploadedFile $image, array $attributes = []): Image
    {
        $merged = array_merge($existing, $attributes);
        $model = $this->image()
                      ->where('name', $existing['name'])
                      ->first();

        if (is_null($model)) {
            $model = $this->createImage($image, $merged);
        } else {
            $model->updateFromFile($image, $merged);
        }

        return $model;
    }
}
