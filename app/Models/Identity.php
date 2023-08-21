<?php

namespace App\Models;

use App\Enums\Identity\IdentityType;
use App\Events\Api\v1\Identity\IdentityCreated;
use App\Exceptions\Api\v1\Identity\IdentityAlreadyVerified;
use App\Http\Requests\Api\v1\ApiRequest;
use App\Schemas\Credentials\CredentialsSchema;
use App\Traits\Models\{ HasTypeValue, HasVerify };

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Http\Request;

class Identity extends Model
{
    use HasTypeValue, HasVerify;

    /*
    |--------------------------------------------------------------------------
    | Properties
    |--------------------------------------------------------------------------
    */

    protected $appends = [
        'is_oauth',
        'is_verified',
    ];

    protected $fillable = [
        'user_id',
        'name',
        'type',
        'value',
        'provider',
    ];

    protected $visible = [
        'is_oauth',
        'is_verified',
        'user_id',
        'name',
        'type',
        'value',
        'provider',
        'verified_at',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'type' => IdentityType::class,
        'verified_at' => 'datetime',
    ];

    protected $dispatchesEvents = [
        'created' => IdentityCreated::class,
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tokens()
    {
        return $this->hasMany(Token::class);
    }

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
    public function isOauth(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->type === IdentityType::OAuth,
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function isVerified(): Attribute
    {
        return Attribute::make(
            get: fn () => (bool) $this->verified_at,
        );
    }

    /**
     * @return void
     */
    public function setVerifiedAtAttribute($value): void
    {
        if (is_null($this->verified_at)) {
            $this->attributes['verified_at'] = $value;
        } else {
            throw new IdentityAlreadyVerified;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Attempts to retrieve an instance of the Identity defined in the request.
     *
     * @param  array  $fields  The raw credentials data.
     *
     * @return \App\Models\Identity|null
     */
    static public function findFromFields(array $fields): Identity|null
    {
        $option = CredentialsSchema::IDENTITY_ONLY;
        $fields = CredentialsSchema::validated($fields, $option);

        if (isset($fields['identity_id'])) {
            return self::find($fields['identity_id']);
        }

        return self::where($fields['identity'])->first();
    }

    /**
     * Attempts to retrieve an instance of the Identity model defined in the request.
     *
     * @param  \Illuminate\Http\Request|\App\Http\Requests\Api\v1\ApiRequest  $request
     *
     * @return \App\Models\Identity|null
     */
    static public function findFromRequest(Request|ApiRequest $request): Identity|null
    {
        return self::findFromFields($request->all([ 'identity_id', 'identity.type', 'identity.value' ]));
    }

    /**
     * Creates a new Identity model using the passed fields that are assumed
     * to have come from a request (e.g. a register request).
     *
     * @param  \App\Models\User  $user  The user to associate the new Identity with.
     * @param  array  $fields  The raw credentials data.
     *
     * @return \App\Models\Identity|null
     */
    static public function createFromFields(User $user, array $fields): Identity
    {
        $option = CredentialsSchema::IDENTITY_ONLY;
        $fields = CredentialsSchema::validated($fields, $option);

        return self::create([
            'user_id' => $user->id,
            'type' => $fields['identity']['type'],
            'value' => $fields['identity']['value'] ?? null,
            'provider' => $fields['identity']['provider'] ?? null,
        ]);
    }
}
