<?php

namespace App\Traits\Models;

trait HasSecretCode
{
    /*
    |--------------------------------------------------------------------------
    | Member Variables
    |--------------------------------------------------------------------------
    */

    /**
     * The plaintext code that is saved before being hashed.
     *
     * @var string
     */
    public $temporary_plaintext_code;

    /*
    |--------------------------------------------------------------------------
    | Utility
    |--------------------------------------------------------------------------
    */

    /**
     * Retrieve the current plaintext code. After retrieval the code will be
     * deleted and cannot be retrieved again.
     *
     * @return string|null
     */
    public function consumePlaintextCode()
    {
        $code = $this->temporary_plaintext_code;
        unset($this->temporary_plaintext_code);
        return $code;
    }

    /*
    |--------------------------------------------------------------------------
    | Attributes
    |--------------------------------------------------------------------------
    */

    /**
     * Hash the plaintext code and assign it.
     *
     * @param  string  $plaintext_code
     *
     * @return void
     */
    public function setCodeAttribute(string $plaintext_code)
    {
        $this->temporary_plaintext_code = $plaintext_code;

        if ($this->algo === 'encrypted') {
            $this->attributes['code'] = $plaintext_code;
        } else {
            $this->attributes['code'] = hash($this->algo ?? 'sha256', $plaintext_code);
        }
    }
}
