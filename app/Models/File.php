<?php

namespace App\Models;

use App\Events\Api\v1\Media\MediaCreating;
use App\Traits\Models\{ HasFileUpload, HasOwnership, HasQueryConstraints };

use Illuminate\Database\Eloquent\{ Model, SoftDeletes };
use Illuminate\Http\UploadedFile;

class File extends Model
{
    use SoftDeletes, HasOwnership, HasQueryConstraints;
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
        'folder_id',
        'owner_id',
        'owner_type',
    ];

    protected $fillable = [
        'disk',
        'name',
        'mimetype',
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
     * Create a new File model from an uploaded file.
     *
     * @param  \Illuminate\Http\UploadedFile  $file  The file uploaded by a user.
     * @param  array  $fields  The attributes passed to the create function, typically coming from a request.
     *
     * @return \App\Models\File
     */
    static public function createFromFile(UploadedFile $file, array $fields)
    {
        return self::createFromFileBase($file, $fields, config('uploads.file.dest'));
    }

    /**
     * Modify an existing File model using an uploaded file.
     *
     * @param  \Illuminate\Http\UploadedFile  $file  The file uploaded by a user.
     * @param  array  $fields  The attributes passed to the update function, typically coming from a request.
     *
     * @return bool
     */
    public function updateFromFile(UploadedFile $file, array $fields): bool
    {
        return call_user_func(
            [ $this, 'updateFromFileBase' ],
            $file,
            $fields,
            config('uploads.file.dest'),
        );
    }
}
