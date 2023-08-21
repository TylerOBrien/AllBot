<?php

namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasFullName
{
    /**
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function fullName(): Attribute
    {
        return Attribute::make(
            get: function () {
                $full_name = implode(' ', array_filter([$this->first_name, $this->last_name]));

                if (empty($full_name)) {
                    return null;
                }

                return $full_name;
            },
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function fullNameReversed()
    {
        return Attribute::make(
            get: function () {
                $full_name_reversed = implode(', ', array_filter([$this->last_name, $this->first_name]));

                if (empty($full_name_reversed)) {
                    return null;
                }

                return $full_name_reversed;
            },
        );
    }
}
