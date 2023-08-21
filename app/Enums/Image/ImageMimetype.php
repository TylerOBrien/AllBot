<?php

namespace App\Enums\Image;

enum ImageMimetype: string
{
    case BMP = 'image/bmp';
    case GIF = 'image/gif';
    case JPG = 'image/jpeg';
    case PNG = 'image/png';
    case SVG = 'image/svg';
}
