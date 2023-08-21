<?php

namespace App\Models;

use App\Traits\Models\HasQueryConstraints;

use Illuminate\Database\Eloquent\{ Model, SoftDeletes };

class Channel extends Model
{
    use SoftDeletes, HasQueryConstraints;

    /*
    |--------------------------------------------------------------------------
    | Properties
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'owner_user_id',
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
    public function commands()
    {
        return $this->belongsToMany(Command::class)
                    ->using(ChannelCommand::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }
}
