<?php

namespace App\Models;

use App\Enums\Identity\IdentityType;
use App\Traits\Models\HasAccords;
use App\Traits\Models\HasFiles;
use App\Traits\Models\HasImages;
use App\Traits\Models\HasMediaAccords;
use App\Traits\Models\HasSounds;
use App\Traits\Models\HasVideos;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable, HasImages, HasVideos, HasSounds, HasFiles, HasAccords, HasMediaAccords;

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
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function channel()
    {
        return $this->hasOne(Channel::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function email()
    {
        return $this->identity()
                    ->where('type', IdentityType::Email);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function folders()
    {
        return $this->morphToMany(Folder::class, 'owner');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function identity()
    {
        return $this->hasOne(Identity::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function mobile()
    {
        return $this->identity()
                    ->where('type', IdentityType::Mobile);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function tokens()
    {
        return $this->hasManyThrough(Token::class, Identity::class);
    }
}
