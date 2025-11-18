<?php
// Cargar helper de rutas si no está cargado
if (!function_exists('route')) {
    require_once dirname(dirname(dirname(__DIR__))) . '/vendor/autoload.php';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistema de Gestión de Pacientes - Clínica SaludTotal">
    <meta name="author" content="SaludTotal">
    <title><?php echo $pageTitle ?? 'SaludTotal'; ?> - Sistema de Gestión de Pacientes</title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo asset('css/style.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('css/theme.css'); ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo asset('img/logo_saludtotal.png'); ?>">
    
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container">
            <div class="logo-container">
                <img src="<?php echo asset('img/logo_saludtotal.png'); ?>" alt="Logo SaludTotal" class="logo">
                <div class="logo-text">
                    <h1>SaludTotal</h1>
                    <p>Sistema de Gestión de Pacientes</p>
                </div>
            </div>
            <div class="header-info" style="text-align: right;">
                <p style="margin: 0; font-size: 14px; opacity: 0.9;">
                    <i class="fas fa-calendar"></i> <?php echo date('d/m/Y'); ?>
                </p>
                <p style="margin: 0; font-size: 14px; opacity: 0.9;">
                    <i class="fas fa-clock"></i> <?php echo date('h:i A'); ?>
                </p>
            </div>
        </div>
    </header>
<body>
