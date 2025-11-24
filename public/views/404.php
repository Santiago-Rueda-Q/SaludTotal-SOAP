<?php require __DIR__ . '/partials/header.php'; ?>

<div class="main-content">
    <div class="error-page">
        <div class="error-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        
        <div class="error-code">404</div>
        
        <h1 class="error-title">Página no encontrada</h1>
        
        <p class="error-message">
            Lo sentimos, la página que estás buscando no existe o el recurso no está disponible.
            Es posible que la URL sea incorrecta o que la página haya sido movida.
        </p>
        
        <div class="error-actions">
            <a href="<?= $baseUrl ?>/index.php" class="btn btn-primary">
                <i class="fas fa-home"></i>
                Volver al Inicio
            </a>
            <a href="<?= $baseUrl ?>/index.php?action=listar_pacientes" class="btn btn-secondary">
                <i class="fas fa-users"></i>
                Ver Pacientes
            </a>
        </div>
    </div>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>