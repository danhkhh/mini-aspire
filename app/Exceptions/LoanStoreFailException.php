<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class LoanStoreFailException extends Exception
{
    public function __construct($message = 'Fail to store loan', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
