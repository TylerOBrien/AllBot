<?php

namespace App\Traits\Models;

use App\Models\Video;

use Illuminate\Http\UploadedFile;

trait HasVideos
{
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function video()
    {
        return $this->morphOne(Video::class, 'owner');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function videos()
    {
        return $this->morphMany(Video::class, 'owner');
    }

    /*
    |--------------------------------------------------------------------------
    | Utilities
    |--------------------------------------------------------------------------
    */

    /**
     * Create a new video instance for this model.
     *
     * @param  string  $name  The name of the video.
     * @param  \Illuminate\Http\UploadedFile  $video  The uploaded video file.
     * @param  array  $fields  The attributes to apply to the video.
     *
     * @return \App\Models\Video
     */
    public function createVideo(UploadedFile $video, array $fields = []): Video
    {
        return Video::createFromFile($video, array_merge($fields, [
            'owner_id' => $this->id,
            'owner_type' => self::class,
        ]));
    }

    /**
     * Update a video with the given name or create a new video instance if one
     * matching the given name does not exist.
     *
     * @param  array<string, mixed>  $existing
     * @param  \Illuminate\Http\UploadedFile  $video  The uploaded video file.
     * @param  array<string, mixed>  $attributes  The attributes to apply to the video.
     *
     * @return \App\Models\Video
     */
    public function updateOrCreateVideo(array $existing, UploadedFile $video, array $attributes = []): Video
    {
        $merged = array_merge($existing, $attributes);
        $model = $this->video()
                      ->where('name', $existing['name'])
                      ->first();

        if (is_null($model)) {
            $model = $this->createVideo($video, $merged);
        } else {
            $model->updateFromFile($video, $merged);
        }

        return $model;
    }
}
