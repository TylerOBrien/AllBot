<?php

namespace App\Models;

use App\Enums\Accord\AccordName;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Accord extends Model
{
    /*
    |--------------------------------------------------------------------------
    | Properties
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'name',
        'category',
        'from_id',
        'from_type',
        'to_id',
        'to_type',
    ];

    protected $casts = [
        'name' => AccordName::class,
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function from()
    {
        return $this->morphTo();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function to()
    {
        return $this->morphTo();
    }

    /*
    |--------------------------------------------------------------------------
    | Utilities
    |--------------------------------------------------------------------------
    */

    /**
     * Returns true if an Accord record exists matching the given attributes.
     *
     * @param  \App\Enums\Accord\AccordName  $name  The name of the Accord relationship.
     * @param  string  $fromType  The class-name of the 'from' model.
     * @param  int  $fromId  The id of the 'from' model.
     * @param  string  $toType  The class-name of the 'to' model.
     * @param  int  $toId  The id of the 'to' model.
     *
     * @return bool
     */
    static public function hasFromTo(AccordName $name, string $fromType, int $fromId, string $toType, int $toId): bool
    {
        return self::where('name', $name)
                   ->where('from_type', Str::start($fromType, config('models.namespace')))
                   ->where('from_id', $fromId)
                   ->where('to_type', Str::start($toType, config('models.namespace')))
                   ->where('to_id', $toId)
                   ->exists();
    }
}
