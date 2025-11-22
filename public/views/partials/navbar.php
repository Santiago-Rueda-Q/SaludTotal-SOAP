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
<!-- Navbar -->
<nav class="navbar">
    <div class="container">
        <a href="<?php echo route('home'); ?>" class="nav-btn">
            <i class="fas fa-home"></i> Inicio
        </a>
        <a href="<?php echo route('pacientes.listar'); ?>" class="nav-btn">
            <i class="fas fa-list"></i> Ver Pacientes
        </a>
        <a href="<?php echo route('pacientes.crear'); ?>" class="nav-btn secondary">
            <i class="fas fa-user-plus"></i> Registrar Paciente
        </a>
    </div>
</nav>

<?php if (isset($breadcrumbs) && !empty($breadcrumbs)): ?>
<div class="container">
    <div class="breadcrumb">
        <ul>
            <li><a href="<?php echo route('home'); ?>"><i class="fas fa-home"></i> Inicio</a></li>
            <?php foreach ($breadcrumbs as $crumb): ?>
            <?php if (isset($crumb['url'])): ?>
            <li><a href="<?php echo $crumb['url']; ?>"><?php echo $crumb['name']; ?></a></li>
            <?php else: ?>
            <li><?php echo $crumb['name']; ?></li>
            <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
<?php endif; ?>
</body>
</html>
