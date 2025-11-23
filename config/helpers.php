<?php

if (!function_exists('dd')) {
    function dd(...$vars): void
    {
        echo "<pre>";
        foreach ($vars as $v) {
            var_dump($v);
        }
        echo "</pre>";
        die(1);
    }
}

if (!function_exists('view_path')) {
    function view_path(string $view): string
    {
        return PUBLIC_PATH . '/views/' . $view;
    }
}
