<?php

namespace App\Models;

use App\Enums\Hash\HashAlgorithm;
use App\Enums\Token\{ TokenAlgorithm, TokenTransformation, TokenType };
use App\Support\Token as TokenSupport;
use App\Traits\Models\HasUniqueMaker;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    use HasUniqueMaker;

    /*
    |--------------------------------------------------------------------------
    | Properties
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'identity_id',
        'type',
        'transformation',
        'algorithm',
        'value',
        'expires_at',
    ];

    protected $casts = [
        'type' => TokenType::class,
        'transformation' => TokenTransformation::class,
        'algorithm' => TokenAlgorithm::class,
        'last_used_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function identity()
    {
        return $this->belongsTo(Identity::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOneThrough
     */
    public function user()
    {
        return $this->hasOneThrough(User::class, Identity::class, 'id', 'id', 'identity_id', 'user_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Attributes
    |--------------------------------------------------------------------------
    */

    /**
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function isExpired(): Attribute
    {
        return Attribute::make(
            get: fn () => (! is_null($this->expires_at)) && now()->lt($this->expires_at),
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Utilities
    |--------------------------------------------------------------------------
    */

    /**
     * Creates a new token instance for the given identity or user.
     *
     * @param  \App\Models\Identity|\App\Models\User  $model  The model instance to create the token for.
     *
     * @return \App\Models\Token
     */
    static public function createFor(Identity|User $model): Token
    {
        return Token::create([
            'identity_id' => $model instanceof Identity ? $model->id : $model->identity->id,
            'type' => TokenType::Api,
            'algorithm' => TokenAlgorithm::SHA384,
            'transformation' => TokenTransformation::Hashed,
            'value' => self::makeUniqueString('value', 32, HashAlgorithm::SHA384),
            'expires_at' => config('auth.tokens.ttl.enabled') ? now()->add(TokenSupport::ttl()) : null,
        ]);
    }

    /**
     * Attempts to get an instance of a Token matching the passed Bearer.
     *
     * @param  string  $bearer  The bearer token value.
     *
     * @return \App\Models\Token|null
     */
    static public function findFromBearer(string $bearer): Token|null
    {
        if (strpos($bearer, '|') === false) {
            $token = self::where('hash', hash(config('auth.tokens.hash.algorithm'), $bearer))
                         ->first();

            if ($token?->type === TokenType::Api) {
                return $token;
            } else {
                return null;
            }
        }

        [ $model_id, $plaintext_value ] = explode('|', $bearer, 2);

        $token = self::find($model_id);

        if ($token?->type !== TokenType::Api) {
            return null;
        }

        $hashed_value = hash($token->algorithm, $plaintext_value);

        if (hash_equals($token->value, $hashed_value)) {
            return $token;
        }

        return null;
    }

    /*
    |--------------------------------------------------------------------------
    | Boot
    |--------------------------------------------------------------------------
    */

    /**
     * @return void
     */
    static public function boot()
    {
        parent::boot();
        static::creating(function (Token $token) {
            $token->hash = hash(config('auth.tokens.hash.algorithm'), $token->value);
            $token->value = match ($token->transformation) {
                TokenTransformation::Encrypted => encrypt($token->value),
                TokenTransformation::Hashed => hash($token->algorithm->value, $token->value),
            };
        });
    }
}
