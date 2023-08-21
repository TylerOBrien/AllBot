<?php

namespace App\Support\Token;

use App\Models\Token;

use Illuminate\Contracts\Support\{ Arrayable, Jsonable };

class TokenPlaintextPair implements Arrayable, Jsonable
{
    /**
     * The access token instance.
     *
     * @var \App\Models\Token
     */
    public $pat;

    /**
     * The plaintext version of the token.
     *
     * @var string
     */
    public $plaintext;

    /**
     * Create a new access token result.
     *
     * @param  \App\Models\Token  $pat
     * @param  string  $plaintext
     *
     * @return void
     */
    public function __construct(Token $pat, string $plaintext)
    {
        $this->pat = $pat;
        $this->plaintext = $plaintext;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'pat' => $this->pat,
            'plaintext' => $this->plaintext,
        ];
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int  $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }
}
