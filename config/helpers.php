<?php
/**
 * Funciones Helper Globales
 * Este archivo define funciones en el namespace global (raíz)
 * para que estén disponibles en TODA la aplicación
 */

if (!function_exists('route')) {
    function route($name, $params = []) {
        return \App\Helpers\RouteHelper::route($name, $params);
    }
}

if (!function_exists('asset')) {
    function asset($path) {
        return \App\Helpers\RouteHelper::asset($path);
    }
}

if (!function_exists('redirect')) {
    function redirect($name, $params = []) {
        \App\Helpers\RouteHelper::redirect($name, $params);
    }
}

if (!function_exists('is_active')) {
    function is_active($routeName) {
        return \App\Helpers\RouteHelper::isActive($routeName);
    }
}

if (!function_exists('base_url')) {
    function base_url($path = '') {
        return \App\Helpers\RouteHelper::baseUrl($path);
    }
}

if (!function_exists('soap_url')) {
    function soap_url($endpoint) {
        return \App\Helpers\RouteHelper::api($endpoint);
    }
}

// Alias de compatibilidad
if (!function_exists('api_url')) {
    function api_url($endpoint) {
        return soap_url($endpoint);
    }
}

if (!function_exists('validate_cedula')) {
    function validate_cedula($cedula) {
        return \App\Helpers\ValidationHelper::validateCedula($cedula);
    }
}

if (!function_exists('validate_email')) {
    function validate_email($email) {
        return \App\Helpers\ValidationHelper::validateEmail($email);
    }
}

if (!function_exists('validate_phone')) {
    function validate_phone($phone) {
        return \App\Helpers\ValidationHelper::validatePhone($phone);
    }
}

if (!function_exists('sanitize_string')) {
    function sanitize_string($string) {
        return \App\Helpers\ValidationHelper::sanitizeString($string);
    }
}
