<?php

namespace App\Enums\Video;

enum VideoMimetype: string
{
    case MOV = 'video/mov';
    case MP4 = 'video/mp4';
    case MKV = 'video/mkv';
}
