<?php

namespace App\Models;

use App\Traits\Models\HasQueryConstraints;

use Illuminate\Database\Eloquent\{ Model, SoftDeletes };

class Command extends Model
{
    use SoftDeletes, HasQueryConstraints;

    /*
    |--------------------------------------------------------------------------
    | Properties
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'name',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function channels()
    {
        return $this->belongsToMany(Channel::class)
                    ->using(ChannelCommand::class);
    }
}
