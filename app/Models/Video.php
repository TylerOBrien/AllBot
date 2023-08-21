<?php

namespace App\Models;

use App\Events\Api\v1\Media\MediaCreating;
use App\Support\VideoStats;
use App\Traits\Models\{ HasFileUpload, HasOwnership, HasQueryConstraints };

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

class Video extends Model
{
    use HasOwnership, HasQueryConstraints;
    use HasFileUpload {
        createFromFile as protected createFromFileBase;
        updateFromFile as protected updateFromFileBase;
    }

    /*
    |--------------------------------------------------------------------------
    | Properties
    |--------------------------------------------------------------------------
    */

    protected $appends = [
        'url',
    ];

    protected $hidden = [
        'disk',
        'filepath',
        'owner_id',
        'owner_type',
    ];

    protected $fillable = [
        'disk',
        'name',
        'mimetype',
        'width',
        'height',
        'duration',
        'filepath',
        'filesize',
        'folder_id',
        'owner_id',
        'owner_type',
    ];

    protected $dispatchesEvents = [
        'creating' => MediaCreating::class,
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Create a new Video model from an uploaded file.
     *
     * @param  \Illuminate\Http\UploadedFile  $file  The file uploaded by a user.
     * @param  array  $fields  The attributes passed to the create function, typically coming from a request.
     *
     * @return \App\Models\Video
     */
    static public function createFromFile(UploadedFile $file, array $fields)
    {
        return self::createFromFileBase(
            $file,
            array_merge($fields, VideoStats::fromFile($file)->toArray()),
            config('uploads.video.dest'),
        );
    }

    /**
     * Modify an existing Video model using an uploaded file.
     *
     * @param  \Illuminate\Http\UploadedFile  $file  The file uploaded by a user.
     * @param  array  $fields  The attributes passed to the update function, typically coming from a request.
     *
     * @return bool
     */
    public function updateFromFile(UploadedFile $file, array $fields): bool
    {
        return call_user_func(
            [$this, 'updateFromFileBase'],
            $file,
            array_merge($fields, VideoStats::fromFile($file)->toArray()),
            config('uploads.video.dest'),
        );
    }
}
