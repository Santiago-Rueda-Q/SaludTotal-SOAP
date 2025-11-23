<?php

namespace App\Exceptions;

use Throwable;

class SoapExceptionHandler
{
    public static function handle(Throwable $e): void
    {
        error_log('[SOAP ERROR] ' . $e->getMessage());
    }
}
