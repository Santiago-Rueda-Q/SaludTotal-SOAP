<?php

namespace App\Helpers;

class RouteHelper
{
    public static function url(string $action = 'home', array $params = []): string
    {
        $query = array_merge(['action' => $action], $params);
        return 'index.php?' . http_build_query($query);
    }
}