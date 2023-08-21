<?php

namespace App\Support;

use Carbon\CarbonInterval;

class Token
{
    /**
     * Returns the number of characters that will be in the generated hash
     * value for the given algorithm.
     *
     * @param  string  $algorithm  The name of the hashing algorithm.
     *
     * @return int
     */
    static public function length(string $algorithm): int
    {
        return match ($algorithm) {
            'md5' => 32,
            'sha1' => 40,
            'sha224' => 56,
            'sha256' => 64,
            'sha384' => 96,
            'sha512' => 128,
            'whirlpool' => 128,
            default => null,
        };
    }

    /**
     * @return \Carbon\CarbonInterval
     */
    static public function ttl(): \Carbon\CarbonInterval
    {
        return call_user_func(
            [CarbonInterval::class, config('auth.tokens.ttl.interval')],
            config('auth.tokens.ttl.amount'),
        );
    }
}
