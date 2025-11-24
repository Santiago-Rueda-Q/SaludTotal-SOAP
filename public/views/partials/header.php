<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistema de gestión de pacientes - Clínica SaludTotal">
    <meta name="author" content="SaludTotal">
    <title>Clínica SaludTotal - Sistema de Gestión SOAP</title>

    <?php
    $baseUrl = '/public';
    ?>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= $baseUrl ?>/assets/img/logo_saludtotal.png">
    <link rel="shortcut icon" type="image/png" href="<?= $baseUrl ?>/assets/img/logo_saludtotal.png">
    <link rel="apple-touch-icon" href="<?= $baseUrl ?>/assets/img/logo_saludtotal.png">

    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer">

    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/style.css">
</head>

<body>
    <div class="app-container">
        <?php require __DIR__ . '/navbar.php'; ?>

        <div id="notification-container" class="notification-container"></div>