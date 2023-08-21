<?php

namespace App\Enums\Upload;

enum UploadDisk: string
{
    case PrivateDirectory = 'local';
    case PublicDirectory = 'public';
    case S3 = 's3';
}
