<?php


namespace App\Utility;

class CustomResponse
{
    public $body;
    public $message;
    public $count;

    /**
     * CustomResponse constructor.
     * @param $body
     * @param $message
     */
    public function __construct($body, $message = "", $count = null)
    {
        $this->body = $body;
        $this->message = $message;
        $this->count = $count;
    }
}
