<?php

namespace App\Models;

use App\Enums\Secret\SecretType;
use App\Support\OAuth\OAuthDriver;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\{ Crypt, Hash };

class Secret extends Model
{
    /*
    |--------------------------------------------------------------------------
    | Properties
    |--------------------------------------------------------------------------
    */

    protected $hidden = [
        'value',
    ];

    protected $fillable = [
        'user_id',
        'type',
        'value',
    ];

    protected $casts = [
        'type' => SecretType::class,
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Attributes
    |--------------------------------------------------------------------------
    */

    /**
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function value(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => match ($this->type) {
                SecretType::Password => $value,
                default => Crypt::decryptString($value),
            },
            set: fn ($value) => match ($this->type) {
                SecretType::Password => Hash::make($value),
                default => Crypt::encryptString($value),
            },
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Create a new secret instance for the given identity.
     *
     * @param  \App\Models\Identity  $identity  The identity instance.
     * @param  array  $fields  The data representing the secret.
     *
     * @return \App\Models\Secret
     */
    static public function createFor(Identity $identity, array $fields): Secret
    {
        return self::create([
            'user_id' => $identity->user_id,
            'type' => $fields['secret']['type'],
            'value' =>  $identity->is_oauth ? OAuthDriver::user($identity)->token : $fields['secret']['value'],
        ]);
    }
}
