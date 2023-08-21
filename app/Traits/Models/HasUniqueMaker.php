<?php

namespace App\Traits\Models;

use App\Enums\Hash\HashAlgorithm;

use Illuminate\Support\Str;

trait HasUniqueMaker
{
    /**
     * Generates a unique cryptographically secure random integer.
     *
     * @param  string  $column  The column to compare against.
     * @param  int  $digits  The number of digits to use in the unique number.
     * @param  \App\Enums\Hash\HashAlgorithm|null  $hash_algo  The hashing algorithm, if any, to use.
     *
     * @return int
     */
    static public function makeUniqueInt(string $column, int $digits = 16, HashAlgorithm|null $hash_algo = null): int
    {
        $min = intval(str_repeat('1', $digits));
        $max = intval(str_repeat('9', $digits));

        do {
            $id = random_int($min, $max);
            $maybe_hashed = $hash_algo ? hash($hash_algo->value, $id) : $id;
        } while (self::where([$column => $maybe_hashed])->exists());

        return $id;
    }

    /**
     * Generates a unique cryptographically secure random string.
     *
     * @param  string  $column  The column to compare against.
     * @param  int  $length  The number of characters to use in the unique string.
     * @param  \App\Enums\Hash\HashAlgorithm|null  $hash_algo  The hashing algorithm, if any, to use.
     *
     * @return string
     */
    static public function makeUniqueString(string $column, int $length = 16, HashAlgorithm|null $hash_algo = null): string
    {
        do {
            $string = Str::random($length);
            $maybe_hashed = $hash_algo ? hash($hash_algo->value, $string) : $string;
        } while (self::where([$column => $maybe_hashed])->exists());

        return $string;
    }
}
