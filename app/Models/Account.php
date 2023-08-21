<?php

namespace App\Models;

use App\Enums\Account\AccountStatus;
use App\Events\Api\v1\Account\AccountCreated;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    /*
    |--------------------------------------------------------------------------
    | Properties
    |--------------------------------------------------------------------------
    */

    protected $casts = [
        'status' => AccountStatus::class,
        'is_enabled' => 'boolean',
    ];

    protected $dispatchesEvents = [
        'created' => AccountCreated::class,
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
