<?php

namespace App\Exceptions;

use Exception;

class RepositoryException extends Exception
{
    public function __construct(
        $message,
        $code = null,
        Exception $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}