<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'SaludTotal - Sistema de Gestión de Pacientes'; ?></title>
    
    <!-- Favicon -->
    <link rel="icon" href="<?php echo asset('img/logo_saludtotal.png'); ?>" type="image/png">
    
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo asset('css/style.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('css/theme.css'); ?>">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <?php
        $config = \App\Core\SoapConfig::getInstance();
        $basePath = rtrim($config->get('base_path', ''), '/');
    ?>
    <script>
        window.APP_BASE_PATH = '<?php echo $basePath; ?>';
        window.SOAP_BASE_URL = '<?php echo $basePath; ?>/soap/pacientes';
    </script>
</head>
<body>
    <div class="wrapper">
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
