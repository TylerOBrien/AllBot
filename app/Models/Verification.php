<?php

namespace App\Models;

use App\Enums\Verification\{ VerificationAbility, VerificationType };
use App\Events\Api\v1\Verification\VerificationCreated;
use App\Exceptions\Api\v1\Verification\MissingHashAlgo;
use App\Traits\Models\{ HasSecretCode, HasUniqueMaker };

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Verification extends Model
{
    use HasSecretCode, HasUniqueMaker;

    /*
    |--------------------------------------------------------------------------
    | Properties
    |--------------------------------------------------------------------------
    */

    protected $hidden = [
        'code',
        'verifiable_id',
        'verifiable_type',
    ];

    protected $fillable = [
        'ability',
        'code',
        'algo',
        'verifiable_id',
        'verifiable_type',
    ];

    protected $casts = [
        'type' => VerificationType::class,
        'ability' => VerificationAbility::class,
        'expires_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    protected $dispatchesEvents = [
        'created' => VerificationCreated::class,
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function verifiable()
    {
        return $this->morphTo();
    }

    /*
    |--------------------------------------------------------------------------
    | Attributes
    |--------------------------------------------------------------------------
    */

    /**
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function isVerified(): Attribute
    {
        return Attribute::make(
            get: fn () => (bool) $this->verified_at,
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Generate a new unique verification code. The generated code will not be
     * persisted in storage.
     *
     * @return string
     *
     * @throws \App\Exceptions\Api\v1\Verification\MissingHashAlgo
     */
    public function generate(): string
    {
        if (is_null($this->algo)) {
            throw new MissingHashAlgo;
        }

        return self::makeUniqueString(
            'code',
            config('verify.default.length'),
            $this->algo,
        );
    }

    /**
     * @return void
     */
    static public function boot()
    {
        parent::boot();
        static::creating(function (Verification $verification) {
            if (is_null($verification->algo)) {
                $verification->algo = config('verify.default.hash_algo');
            }

            if (is_null($verification->code)) {
                $verification->code = $verification->generate();
            }
        });
    }
}
