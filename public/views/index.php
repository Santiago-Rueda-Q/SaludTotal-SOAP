<?php require __DIR__ . '/partials/header.php'; ?>

<div class="main-content">
    <div class="hero-section">
        <h1>
            <i class="fas fa-hospital"></i>
            Clínica SaludTotal
        </h1>
        <p>Sistema integral de gestión de pacientes basado en arquitectura SOAP. Administre de forma eficiente todos los registros médicos de su clínica.</p>
    </div>

    <div class="features-grid">
        <div class="feature-card">
            <div class="icon">
                <i class="fas fa-user-plus"></i>
            </div>
            <h3>Registro de Pacientes</h3>
            <p>Agregue nuevos pacientes al sistema con toda su información médica y de contacto de forma rápida y segura.</p>
        </div>

        <div class="feature-card">
            <div class="icon">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <h3>Lista de Pacientes</h3>
            <p>Visualice y gestione todos los pacientes registrados con una interfaz clara y organizada.</p>
        </div>

        <div class="feature-card">
            <div class="icon">
                <i class="fas fa-edit"></i>
            </div>
            <h3>Edición de Datos</h3>
            <p>Actualice la información de los pacientes cuando sea necesario manteniendo un historial completo.</p>
        </div>

        <div class="feature-card">
            <div class="icon">
                <i class="fas fa-database"></i>
            </div>
            <h3>Gestión Completa</h3>
            <p>Elimine registros obsoletos de forma segura con confirmación para evitar pérdidas accidentales.</p>
        </div>
    </div>

    <div class="btn-group">
        <a href="index.php?action=crear_paciente" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i>
            <span>Registrar Nuevo Paciente</span>
        </a>
        <a href="index.php?action=listar_pacientes" class="btn btn-secondary">
            <i class="fas fa-list-ul"></i>
            <span>Ver Todos los Pacientes</span>
        </a>
    </div>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>