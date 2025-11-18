<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistema de Gestión de Pacientes - Clínica SaludTotal">
    <meta name="author" content="SaludTotal">
    <title><?php echo $pageTitle ?? 'SaludTotal'; ?> - Sistema de Gestión de Pacientes</title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="/public/assets/css/style.css">
    <link rel="stylesheet" href="/public/assets/css/theme.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/public/assets/img/logo_saludtotal.png">
    
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<!-- Footer -->
    <footer>
        <div class="container">
            <p style="margin: 5px 0;">
                <strong>SaludTotal</strong> - Sistema de Gestión de Pacientes
            </p>
            <p style="margin: 5px 0; font-size: 14px; opacity: 0.9;">
                &copy; <?php echo date('Y'); ?> Clínica SaludTotal. Todos los derechos reservados.
            </p>
            <p style="margin: 5px 0; font-size: 12px; opacity: 0.8;">
                Desarrollado con tecnología SOAP | Versión 1.0
            </p>
        </div>
    </footer>

    <!-- JavaScript -->
    <script src="<?php echo asset('js/main.js'); ?>"></script>
    <script src="<?php echo asset('js/alerts.js'); ?>"></script>
    
    <!-- Scripts adicionales por página -->
    <?php if (isset($pageScripts)): ?>
        <?php foreach ($pageScripts as $script): ?>
            <script src="<?php echo $script; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>