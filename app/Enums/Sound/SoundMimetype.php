<?php

namespace App\Enums\Sound;

enum SoundMimetype: string
{
    case MP3 = 'audio/mp3';
    case OGG = 'audio/ogg';
    case WAV = 'audio/wav';
}
