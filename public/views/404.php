<?php
/**
 * Página 404 - No Encontrada
 */
$pageTitle = 'Página no encontrada';
include_once __DIR__ . '/partials/header.php';
include_once __DIR__ . '/partials/navbar.php';
?>

<div class="container">
    <div class="empty-state" style="padding: 60px 20px;">
        <i class="fas fa-exclamation-triangle" style="font-size: 80px; color: #dc3545; margin-bottom: 30px;"></i>
        <h1 style="font-size: 72px; color: #696a69; margin: 0;">404</h1>
        <h2 style="color: #39aaa7; margin: 20px 0;">Página no encontrada</h2>
        <p style="font-size: 18px; color: #696a69; max-width: 600px; margin: 20px auto;">
            Lo sentimos, la página que estás buscando no existe o ha sido movida.
        </p>
        
        <div style="margin-top: 40px; display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
            <a href="<?php echo route('home'); ?>" class="btn btn-primary">
                <i class="fas fa-home"></i> Ir al Inicio
            </a>
            <a href="<?php echo route('pacientes.listar'); ?>" class="btn btn-secondary">
                <i class="fas fa-list"></i> Ver Pacientes
            </a>
            <a href="<?php echo route('pacientes.crear'); ?>" class="btn btn-success">
                <i class="fas fa-plus"></i> Nuevo Paciente
            </a>
        </div>
        
        <div style="margin-top: 60px; padding: 30px; background: #f8f9fa; border-radius: 10px; max-width: 600px; margin-left: auto; margin-right: auto;">
            <h4 style="color: #39aaa7; margin-bottom: 15px;">
                <i class="fas fa-info-circle"></i> ¿Necesitas ayuda?
            </h4>
            <p style="color: #696a69; line-height: 1.8;">
                Si crees que esto es un error, por favor contacta al administrador del sistema o utiliza los enlaces de navegación del menú superior.
            </p>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/partials/footer.php'; ?>