<?php

namespace App\Schemas\Profile;

use App\Schemas\Schema;

class ProfileSchema extends Schema
{
    /**
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'owner_id' => 'required|int',
            'owner_type' => 'required|string',
        ];
    }
}
