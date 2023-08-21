<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Enums
    |--------------------------------------------------------------------------
    |
    | The values for all enum columns in the database.
    |
    | These are used in table creation migrations, so the side-effects of
    | modifying enum settings that are already in use must be considered
    | otherwise there will be a risk of truncated data in the database.
    |
    */

    'account' => [
        'status' => \App\Support\Enum::values(\App\Enums\Account\AccountStatus::class),
    ],

    'identity' => [
        'type' => \App\Support\Enum::values(\App\Enums\Identity\IdentityType::class),
    ],

    'image' => [
        'breakpoint' => \App\Support\Enum::values(\App\Enums\Image\ImageBreakpoint::class),
        'orientation' => \App\Support\Enum::values(\App\Enums\Image\ImageOrientation::class),
        'mimetype' => \App\Support\Enum::values(\App\Enums\Image\ImageMimetype::class),
    ],

    'oauth' => [
        'provider' => \App\Support\Enum::values(\App\Enums\OAuth\OAuthProvider::class),
    ],

    'profile-field' => [
        'type' => \App\Support\Enum::values(\App\Enums\Profile\ProfileFieldType::class),
    ],

    'secret' => [
        'type' => \App\Support\Enum::values(\App\Enums\Secret\SecretType::class),
    ],

    'subscribe' => [
        'tier' => \App\Support\Enum::values(\App\Enums\Subscribe\SubscribeTier::class),
    ],

    'upload' => [
        'disk' => \App\Support\Enum::values(\App\Enums\Upload\UploadDisk::class),
    ],

    'token' => [
        'algorithm' => \App\Support\Enum::values(\App\Enums\Token\TokenAlgorithm::class),
        'transformation' => \App\Support\Enum::values(\App\Enums\Token\TokenTransformation::class),
        'type' => \App\Support\Enum::values(\App\Enums\Token\TokenType::class),
    ],

    'video' => [
        'mimetype' => \App\Support\Enum::values(\App\Enums\Video\VideoMimetype::class),
    ],

];
