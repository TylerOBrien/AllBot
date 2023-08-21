<?php

namespace App\Traits\Models;

use App\Enums\Accord\AccordName;
use App\Models\Accord;

use Illuminate\Support\Str;

trait HasAccords
{
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * @param  string  $relatedClassName  The class name of the model being accorded.
     * @param  AccordName|null  $accordName  The name/identifier of the accord.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOneThrough
     */
    public function accordsToOne(string $relatedClassName, AccordName|null $accordName = null)
    {
        $related = Str::start($relatedClassName, config('models.namespace'));
        $query = $this->hasOneThrough($related, Accord::class, 'from_id', 'id', null, 'to_id')
                      ->where('accords.to_type', $related)
                      ->where('accords.from_type', self::class);

        if (! is_null($accordName)) {
            return $query->where('accords.name', $accordName);
        }

        return $query;
    }

    /**
     * @param  string  $relatedClassName  The class name of the model being accorded.
     * @param  AccordName|null  $accordName  The name/identifier of the accord.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOneThrough
     */
    public function accordsToMany(string $relatedClassName, AccordName|null $accordName = null)
    {
        $related = Str::start($relatedClassName, config('models.namespace'));
        $query = $this->hasManyThrough($related, Accord::class, 'from_id', 'id', null, 'to_id')
                      ->where('accords.to_type', $related)
                      ->where('accords.from_type', self::class);

        if (! is_null($accordName)) {
            return $query->where('accords.name', $accordName);
        }

        return $query;
    }

    /*
    |--------------------------------------------------------------------------
    | Utilities
    |--------------------------------------------------------------------------
    */

    /**
     * Creates a new Accord instance.
     *
     * @param  AccordName  $name  The name of the Accord relationship.
     * @param  array<string, mixed>  $fields  The attributes to pass to the create function.
     *
     * @return \App\Models\Accord
     */
    public function createAccord(AccordName $name, array $fields): Accord
    {
        return Accord::create(array_merge($fields, [
            'name' => $name->value,
            'from_type' => self::class,
            'from_id' => $this->id,
        ]));
    }

    /**
     * Update the specified Accord if it exists otherwise create a new one.
     *
     * @param  array<string, mixed>  $existing  The name of the Accord relationship.
     * @param  array<string, mixed>  $fields  The attributes to pass to the create function.
     *
     * @return \App\Models\Accord
     */
    public function updateOrCreateAccord(array $existing, array $fields): Accord
    {
        $query = Accord::where('from_type', self::class)
                       ->where('from_id', $this->id);

        foreach ($existing as $column => $value) {
            $query = $query->where($column, $value);
        }

        $accord = $query->first();

        if (is_null($accord)) {
            $accord = $this->createAccord($existing['name'], $fields);
        } else {
            $accord->fill($fields)->save();
        }

        return $accord;
    }
}
