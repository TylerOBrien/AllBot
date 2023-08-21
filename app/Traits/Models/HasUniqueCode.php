<?php

namespace App\Traits\Models;

trait HasUniqueCode
{
    /**
     * The plaintext verification code that is saved before being hashed.
     * 
     * @var string
     */
    protected $temporary_plaintext_code;

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
        $this->attributes['code'] = hash('sha256', $plaintext_code);
    }
}
