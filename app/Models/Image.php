<?php

namespace App\Models;

use App\Enums\Image\{ ImageBreakpoint, ImageMimetype, ImageOrientation };
use App\Events\Api\v1\Image\ImageCreated;
use App\Traits\Models\{ HasFileUpload, HasOwnership, HasQueryConstraints };

use Illuminate\Database\Eloquent\{ Model, SoftDeletes };
use Illuminate\Http\UploadedFile;

class Image extends Model
{
    use SoftDeletes, HasQueryConstraints, HasOwnership;
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

    protected $visible = [
        'id',
        'name',
        'mimetype',
        'filesize',
        'breakpoint',
        'orientation',
        'width',
        'height',
        'url',
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'disk',
        'name',
        'mimetype',
        'filepath',
        'filesize',
        'orientation',
        'width',
        'height',
        'owner_id',
        'owner_type',
    ];

    protected $casts = [
        'mimetype' => ImageMimetype::class,
        'breakpoint' => ImageBreakpoint::class,
        'orientation' => ImageOrientation::class,
    ];

    protected $dispatchesEvents = [
        'created' => ImageCreated::class,
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
     * Create a new Image model from an uploaded file.
     *
     * @param  \Illuminate\Http\UploadedFile  $file  The file uploaded by a user.
     * @param  array  $fields  The attributes passed to the create function, typically coming from a request.
     *
     * @return \App\Models\Image
     */
    static public function createFromFile(UploadedFile $file, array $fields): Image
    {
        [ $width, $height ] = getimagesize($file);

        $fields['width'] = $width;
        $fields['height'] = $height;

        return self::createFromFileBase($file, $fields, config('uploads.image.dest'));
    }

    /**
     * Modify an existing Image model using an uploaded file.
     *
     * @param  \Illuminate\Http\UploadedFile  $file  The file uploaded by a user.
     * @param  array  $fields  The attributes passed to the update function, typically coming from a request.
     *
     * @return bool
     */
    public function updateFromFile(UploadedFile $file, array $fields): bool
    {
        [ $width, $height ] = getimagesize($file);

        $fields['width'] = $width;
        $fields['height'] = $height;

        return call_user_func(
            [ $this, 'updateFromFileBase' ],
            $file,
            $fields,
            config('uploads.image.dest'),
        );
    }
}
