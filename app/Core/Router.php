<?php
namespace App\Core;

/**
 * Sistema de Rutas para SaludTotal
 * Maneja el enrutamiento de la aplicación de forma segura
 */
class Router
{
    private static $instance = null;
    private $routes = [];
    private $currentRoute = null;
    private $basePath = '';
    
    /**
     * Constructor privado para Singleton
     */
    private function __construct()
    {
        $config = SoapConfig::getInstance();
        $this->basePath = rtrim($config->get('base_path', ''), '/');
        $this->registerRoutes();
    }
    
    /**
     * Obtener instancia única
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Registrar todas las rutas de la aplicación
     */
    private function registerRoutes()
    {
        // Rutas de vistas
        $this->addRoute('GET', '/', 'views/index.php', 'home');
        $this->addRoute('GET', '/inicio', 'views/index.php', 'inicio');
        $this->addRoute('GET', '/pacientes', 'views/listar_pacientes.php', 'pacientes.listar');
        $this->addRoute('GET', '/pacientes/crear', 'views/crear_paciente.php', 'pacientes.crear');
        $this->addRoute('GET', '/pacientes/editar', 'views/editar_paciente.php', 'pacientes.editar');
        
        // Rutas API
        $this->addRoute('GET', '/api/pacientes', 'client/routes.php?action=listar', 'api.pacientes.listar');
        $this->addRoute('GET', '/api/pacientes/buscar', 'client/routes.php?action=buscar', 'api.pacientes.buscar');
        $this->addRoute('POST', '/api/pacientes/crear', 'client/routes.php?action=crear', 'api.pacientes.crear');
        $this->addRoute('POST', '/api/pacientes/actualizar', 'client/routes.php?action=actualizar', 'api.pacientes.actualizar');
        $this->addRoute('POST', '/api/pacientes/eliminar', 'client/routes.php?action=eliminar', 'api.pacientes.eliminar');
        
        // Ruta del servidor SOAP
        $this->addRoute('POST', '/soap/server', 'server/index.php', 'soap.server');
        $this->addRoute('GET', '/soap/wsdl', 'wsdl/pacientes.wsdl', 'soap.wsdl');
    }
    
    /**
     * Agregar una ruta
     */
    private function addRoute($method, $path, $target, $name = null)
    {
        $pattern = $this->createPattern($path);
        
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'pattern' => $pattern,
            'target' => $target,
            'name' => $name
        ];
    }
    
    /**
     * Crear patrón regex para la ruta
     */
    private function createPattern($path)
    {
        // Convertir parámetros {param} a regex
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $path);
        $pattern = '#^' . $pattern . '$#';
        return $pattern;
    }
    
    /**
     * Resolver la ruta actual
     */
    public function resolve()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = $this->getUri();
        
        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }
            
            if (preg_match($route['pattern'], $uri, $matches)) {
                $this->currentRoute = $route;
                
                // Extraer parámetros de la ruta
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                
                return $this->dispatch($route['target'], $params);
            }
        }
        
        // Ruta no encontrada
        $this->notFound();
    }
    
    /**
     * Obtener URI limpia
     */
    private function getUri()
    {
        $uri = $_SERVER['REQUEST_URI'];
        
        // Remover query string
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        
        // Remover base path
        if (!empty($this->basePath)) {
            $uri = substr($uri, strlen($this->basePath));
        }
        
        // Normalizar
        $uri = '/' . trim($uri, '/');
        
        // Si es raíz, devolver /
        if ($uri === '/') {
            return '/';
        }
        
        return rtrim($uri, '/');
    }
    
    /**
     * Despachar la ruta
     */
    private function dispatch($target, $params = [])
    {
        // Agregar parámetros a $_GET si existen
        foreach ($params as $key => $value) {
            $_GET[$key] = $value;
        }
        
        // Si el target contiene query string, agregarla
        if (strpos($target, '?') !== false) {
            list($file, $query) = explode('?', $target, 2);
            parse_str($query, $queryParams);
            $_GET = array_merge($_GET, $queryParams);
            $target = $file;
        }
        
        // Construir ruta completa del archivo
        $publicPath = dirname(dirname(__DIR__)) . '/public/';
        $filePath = $publicPath . $target;
        
        // Verificar que el archivo existe
        if (!file_exists($filePath)) {
            $this->notFound();
            return;
        }
        
        // Incluir el archivo
        require $filePath;
        exit;
    }
    
    /**
     * Generar URL por nombre de ruta
     */
    public function url($name, $params = [])
    {
        foreach ($this->routes as $route) {
            if ($route['name'] === $name) {
                $url = $route['path'];
                
                // Reemplazar parámetros
                foreach ($params as $key => $value) {
                    $url = str_replace('{' . $key . '}', $value, $url);
                }
                
                return $this->basePath . $url;
            }
        }
        
        return '#';
    }
    
    /**
     * Página no encontrada
     */
    private function notFound()
    {
        http_response_code(404);
        $publicPath = dirname(dirname(__DIR__)) . '/public/';
        
        if (file_exists($publicPath . 'views/404.php')) {
            require $publicPath . 'views/404.php';
        } else {
            echo '<h1>404 - Página no encontrada</h1>';
        }
        exit;
    }
    
    /**
     * Redirigir a una ruta
     */
    public function redirect($name, $params = [])
    {
        $url = $this->url($name, $params);
        header("Location: $url");
        exit;
    }
    
    /**
     * Obtener ruta actual
     */
    public function getCurrentRoute()
    {
        return $this->currentRoute;
    }
}