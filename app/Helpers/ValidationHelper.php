<?php

namespace App\Helpers;

class ValidationHelper
{
    public static function sanitizeString(string $value): string
    {
        return trim(filter_var($value, FILTER_SANITIZE_STRING));
    }
}