<?php

namespace App\Exceptions\Api;

use Exception;

class ApiException extends Exception
{
    /**
     * The error message.
     *
     * @var string
     */
    protected $message;


    /**
     * The HTTP status code to be used in the response.
     *
     * @var int
     */
    protected $http_code;

    /**
     * Create a new exception.
     *
     * @param  string  $lang_file  The lang resource to use as the error message.
     * @param  int  $http_code  The HTTP status code to be used in the response.
     * @param  array<string, mixed>  $attributes  The key/value pairs to pass to the lang resource.
     *
     * @return void
     */
    public function __construct(string $lang_file, int $http_code, array $attributes = [])
    {
        $this->message = trans($lang_file, $attributes);
        $this->http_code = $http_code;
    }

    /**
     * Retrieve the error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function render()
    {
        return response()->json(
            [
                trans('responses.key.message') => $this->message,
            ],
            $this->http_code,
        );
    }
}
