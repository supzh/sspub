<?php

namespace Application\Server;

class ServerExceptionHandler
{
    public $exception;
    public $message;

    public function __construct(\Exception $exception)
    {
        $this->exception = $exception;
        $this->message = $this->exception->getMessage();
    }

    public function getView()
    {
        return $this->message;
    }

    public function printView()
    {
        echo $this->message . "\n";
    }

    public function viewExit()
    {
        exit($this->message . "\n");
    }
}
