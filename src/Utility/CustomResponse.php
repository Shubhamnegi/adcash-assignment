<?php


namespace App\Utility;

class CustomResponse
{
    public $body;
    public $message;

    /**
     * CustomResponse constructor.
     * @param $body
     * @param $message
     */
    public function __construct($body, $message = "")
    {
        $this->body = $body;
        $this->message = $message;
    }
}
