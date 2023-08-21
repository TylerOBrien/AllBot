<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ChannelCommand extends Pivot
{
    /*
    |--------------------------------------------------------------------------
    | Properties
    |--------------------------------------------------------------------------
    */

    public $timestamps = false;

    protected $fillable = [
        'channel_id',
        'command_id',
    ];
}
