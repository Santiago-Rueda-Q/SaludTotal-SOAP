<?php

namespace App\Core;

class Router
{
    public function handle(): void
    {
        require \PUBLIC_PATH . '/client/Cliente.php';
    }
}
