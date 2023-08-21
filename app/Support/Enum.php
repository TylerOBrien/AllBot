<?php

namespace App\Support;

class Enum
{
    /**
     * Returns an array containing all keys for the given native PHP enum.
     *
     * @param  string  $enum_class_name  The class name for the native PHP enum.
     *
     * @return array<string>
     */
    static public function keys(string $enum_class_name): array
    {
        return array_map(fn ($case) => $case->name, $enum_class_name::cases());
    }

    /**
     * Returns an array containing all values for the given native PHP enum.
     *
     * @param  string  $enum_class_name  The class name for the native PHP enum.
     *
     * @return array<string>
     */
    static public function values(string $enum_class_name): array
    {
        return array_map(fn ($case) => $case->value, $enum_class_name::cases());
    }
}
