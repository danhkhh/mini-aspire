<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class MakePaymentFailException extends Exception
{
    public function __construct($message = 'Fail to make payment', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
