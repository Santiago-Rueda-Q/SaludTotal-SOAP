<nav class="navbar">
    <a href="<?= $baseUrl ?>/index.php" class="navbar-brand">
        <div class="logo">
            <img src="<?= $baseUrl ?>/assets/img/logo_saludtotal.png" alt="SaludTotal Logo">
        </div>
        <span class="brand-name">SaludTotal</span>
    </a>

    <ul class="navbar-menu">
        <li>
            <a href="<?= $baseUrl ?>/index.php">
                <i class="fas fa-home"></i>
                <span>Inicio</span>
            </a>
        </li>
        <li>
            <a href="<?= $baseUrl ?>/index.php?action=crear_paciente">
                <i class="fas fa-user-plus"></i>
                <span>Registrar</span>
            </a>
        </li>
        <li>
            <a href="<?= $baseUrl ?>/index.php?action=listar_pacientes">
                <i class="fas fa-users"></i>
                <span>Pacientes</span>
            </a>
        </li>
    </ul>
</nav>