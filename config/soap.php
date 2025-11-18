<?php
/**
 * Configuración específica para el servicio SOAP
 */

// Cargar configuración de la aplicación
$appConfigFile = __DIR__ . '/app.php';
$config = file_exists($appConfigFile) ? require $appConfigFile : [];

// Valores por defecto si no existe la configuración
$config = $config ?: [
    'soap' => [
        'wsdl_url' => env('SOAP_WSDL_URL', 'http://localhost/public/wsdl/pacientes.wsdl'),
        'server_url' => env('SOAP_SERVER_URL', 'http://localhost/public/server/index.php'),
        'cache' => WSDL_CACHE_NONE,
        'encoding' => 'UTF-8',
        'soap_version' => SOAP_1_2
    ],
    'debug' => env('APP_DEBUG', false)
];

return [
    // URL del WSDL
    'wsdl_url' => $config['soap']['wsdl_url'] ?? env('SOAP_WSDL_URL', 'http://localhost/public/wsdl/pacientes.wsdl'),
    
    // URL del servidor SOAP
    'server_url' => $config['soap']['server_url'] ?? env('SOAP_SERVER_URL', 'http://localhost/public/server/index.php'),
    
    // Opciones del servidor SOAP
    'server_options' => [
        'uri' => $config['soap']['server_url'] ?? env('SOAP_SERVER_URL', 'http://localhost/public/server/index.php'),
        'encoding' => $config['soap']['encoding'] ?? 'UTF-8',
        'soap_version' => $config['soap']['soap_version'] ?? SOAP_1_2,
        'cache_wsdl' => $config['soap']['cache'] ?? WSDL_CACHE_NONE
    ],
    
    // Opciones del cliente SOAP
    'client_options' => [
        'trace' => $config['debug'] ?? false,
        'exceptions' => true,
        'encoding' => $config['soap']['encoding'] ?? 'UTF-8',
        'soap_version' => $config['soap']['soap_version'] ?? SOAP_1_2,
        'cache_wsdl' => $config['soap']['cache'] ?? WSDL_CACHE_NONE,
        'connection_timeout' => 30,
        'features' => SOAP_SINGLE_ELEMENT_ARRAYS
    ],
    
    // Namespace del servicio
    'namespace' => 'http://saludtotal.com/soap/pacientes',
    
    // Nombre del servicio
    'service_name' => 'PacientesService',
    
    // Operaciones disponibles
    'operations' => [
        'crearPaciente' => [
            'input' => 'CrearPacienteRequest',
            'output' => 'CrearPacienteResponse'
        ],
        'buscarPaciente' => [
            'input' => 'BuscarPacienteRequest',
            'output' => 'BuscarPacienteResponse'
        ],
        'listarPacientes' => [
            'input' => 'ListarPacientesRequest',
            'output' => 'ListarPacientesResponse'
        ],
        'actualizarPaciente' => [
            'input' => 'ActualizarPacienteRequest',
            'output' => 'ActualizarPacienteResponse'
        ],
        'eliminarPaciente' => [
            'input' => 'EliminarPacienteRequest',
            'output' => 'EliminarPacienteResponse'
        ]
    ],
    
    // Tipos complejos
    'complex_types' => [
        'Paciente' => [
            'id' => 'int',
            'cedula' => 'string',
            'nombres' => 'string',
            'apellidos' => 'string',
            'telefono' => 'string',
            'fecha_nacimiento' => 'string',
            'direccion' => 'string',
            'email' => 'string',
            'genero' => 'string',
            'estado' => 'string'
        ],
        'Respuesta' => [
            'exito' => 'boolean',
            'mensaje' => 'string',
            'datos' => 'array'
        ]
    ],
    
    // Configuración de reintentos
    'retry' => [
        'max_attempts' => 3,
        'delay_seconds' => 2
    ],
    
    // Timeouts
    'timeouts' => [
        'connection' => 30,
        'request' => 60
    ]
];