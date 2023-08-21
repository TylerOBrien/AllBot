<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Uploads
    |--------------------------------------------------------------------------
    |
    | Settings for files that have been uploaded by users.
    |
    */

    'default' => [
        'disk' => env('UPLOADS_DEFAULT_DISK', 'public'),
        'dest' => 'upload',
        'filesize' => [
            'max' => '32M',
        ],
    ],

    'files' => [
        'disk' => env('UPLOADS_FILES_DISK', env('UPLOADS_DEFAULT_DISK', 'public')),
        'dest' => 'files',
        'filesize' => [
            'max' => '32M',
        ],
    ],

    'images' => [
        'disk' => env('UPLOADS_IMAGES_DISK', env('UPLOADS_DEFAULT_DISK', 'public')),
        'dest' => 'images',
        'filesize' => [
            'max' => '32M',
        ],
    ],

    'videos' => [
        'disk' => env('UPLOADS_VIDEOS_DISK', env('UPLOADS_DEFAULT_DISK', 'public')),
        'dest' => 'videos',
        'filesize' => [
            'max' => '32M',
        ],
    ],

];
