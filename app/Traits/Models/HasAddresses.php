<?php

namespace App\Traits\Models;

use App\Models\Address;

trait HasAddresses
{
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function address()
    {
        return $this->morphOne(Address::class, 'owner');
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function addresses()
    {
        return $this->morphMany(Address::class, 'owner');
    }

    /*
    |--------------------------------------------------------------------------
    | Utilities
    |--------------------------------------------------------------------------
    */

    /**
     * Create a new address instance.
     *
     * @param  array<string, mixed>  $attributes  The attributes/fields to apply to the address.
     *
     * @return \App\Models\Address
     */
    public function createAddress(array $attributes): Address
    {
        return Address::create(array_merge($attributes, [
            'owner_id' => $this->id,
            'owner_type' => self::class,
        ]));
    }

    /**
     * Update an address matching the existing attributes or create a new address
     * instance if no match can be found.
     *
     * @param  array<string, mixed>  $existing
     * @param  array<string, mixed>  $attributes
     *
     * @return \App\Models\Address
     */
    public function updateOrCreateAddress(array $existing, array $attributes): Address
    {
        $merged = array_merge($existing, $attributes);
        $address = $this->address()
                        ->where('name', $existing['name'])
                        ->first();

        if (is_null($address)) {
            $address = $this->createAddress($merged);
        } else {
            $address->fill($merged)->save();
        }

        return $address;
    }
}
