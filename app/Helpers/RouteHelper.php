<?php
namespace App\Helpers;

use App\Core\Router;
use App\Core\SoapConfig;

/**
 * Helper de Rutas
 */
class RouteHelper
{
    public static function route($name, $params = [])
    {
        $router = Router::getInstance();
        return $router->url($name, $params);
    }
    
    public static function asset($path)
    {
        $config = SoapConfig::getInstance();
        $basePath = $config->get('base_path', '');
        return rtrim($basePath, '/') . '/assets/' . ltrim($path, '/');
    }
    
    public static function redirect($name, $params = [])
    {
        $router = Router::getInstance();
        $router->redirect($name, $params);
    }
    
    public static function isActive($routeName)
    {
        $router = Router::getInstance();
        $currentRoute = $router->getCurrentRoute();
        return $currentRoute && $currentRoute['name'] === $routeName;
    }
    
    public static function baseUrl($path = '')
    {
        $config = SoapConfig::getInstance();
        $url = $config->get('url', 'http://localhost');
        $basePath = $config->get('base_path', '');
        return rtrim($url . $basePath, '/') . '/' . ltrim($path, '/');
    }
    
    public static function api($endpoint)
    {
        return self::baseUrl('soap/' . ltrim($endpoint, '/'));
    }
}
