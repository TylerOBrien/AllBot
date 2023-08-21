<?php

namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait HasFileUpload
{
    /*
    |--------------------------------------------------------------------------
    | Attributes
    |--------------------------------------------------------------------------
    */

    /**
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function url(): Attribute
    {
        return Attribute::make(
            get: function () {
                /** @var \Illuminate\Filesystem\FilesystemAdapter */
                $disk = Storage::disk($this->disk);
                return $disk->url($this->filepath);
            },
        )->shouldCache();
    }

    /*
    |--------------------------------------------------------------------------
    | Utilities
    |--------------------------------------------------------------------------
    */

    /**
     * Create a new instance of this model from an uploaded file.
     *
     * @param  \Illuminate\Http\UploadedFile  $file  The uploaded file.
     * @param  array  $attributes  The attributes/fields to apply to the model that is being created.
     * @param  string|null  $dest  The destination directory for the file.
     * @param  string|null  $disk  The disk to write the file to.
     *
     * @return self
     */
    static public function createFromFile(UploadedFile $file, array $attributes, string|null $dest = null, string|null $disk = null): self
    {
        $disk = $disk ?? config('uploads.default.disk');
        $dest = $dest ?? config('uploads.default.dest');

        $filesize = filesize($file);
        $mimetype = $file->getMimeType();
        $filepath = Storage::disk($disk)->put($dest, $file);

        return self::create(
            array_merge($attributes, compact('disk', 'filesize', 'filepath', 'mimetype'))
        );
    }

    /**
     * Update an existing instance of this model from an uploaded file.
     *
     * @param  \Illuminate\Http\UploadedFile  $file  The uploaded file.
     * @param  array  $attributes  The attributes/fields to apply to the model that is being updated.
     * @param  string|null  $dest  The destination directory for the file.
     *
     * @return bool
     */
    public function updateFromFile(UploadedFile $file, array $attributes, string|null $dest = null): bool
    {
        $dest = $dest ?? config('uploads.default.dest');

        $storage = Storage::disk($this->disk);
        $storage->delete($this->filepath);

        $filesize = filesize($file);
        $mimetype = $file->getMimeType();
        $filepath = $storage->put($dest, $file);

        return $this->fill(array_merge($attributes, compact('filesize', 'filepath', 'mimetype')))
                    ->save();
    }

    /**
     * @return bool
     */
    public function delete()
    {
        Storage::disk($this->disk)
               ->delete($this->filepath);

        return parent::delete();
    }
}
