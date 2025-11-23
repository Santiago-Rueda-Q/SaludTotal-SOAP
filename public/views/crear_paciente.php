<?php require __DIR__ . '/partials/header.php'; ?>

<div class="main-content">
    <h2>
        <i class="fas fa-user-plus"></i>
        Registrar Paciente
    </h2>
    <p style="color: var(--text-secondary); margin-bottom: 32px;">Complete el formulario con la información del paciente</p>

    <?php if (isset($_GET['mensaje'])): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <span><?php echo htmlspecialchars($_GET['mensaje']); ?></span>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-triangle"></i>
            <span><?php echo htmlspecialchars($_GET['error']); ?></span>
        </div>
    <?php endif; ?>

    <form method="POST" action="index.php?action=crear_paciente" id="formRegistrar">
        <div class="form-group">
            <label for="cedula">
                <i class="fas fa-id-card"></i>
                Cédula *
            </label>
            <input type="text" id="cedula" name="cedula" required placeholder="Ej: 1234567890">
        </div>

        <div class="form-group">
            <label for="nombres">
                <i class="fas fa-user"></i>
                Nombres *
            </label>
            <input type="text" id="nombres" name="nombres" required placeholder="Ej: Juan Carlos">
        </div>

        <div class="form-group">
            <label for="apellidos">
                <i class="fas fa-user-tag"></i>
                Apellidos *
            </label>
            <input type="text" id="apellidos" name="apellidos" required placeholder="Ej: Pérez García">
        </div>

        <div class="form-group">
            <label for="telefono">
                <i class="fas fa-phone"></i>
                Teléfono *
            </label>
            <input type="text" id="telefono" name="telefono" required placeholder="Ej: +57 300 123 4567">
        </div>

        <div class="form-group">
            <label for="fecha_nacimiento">
                <i class="fas fa-calendar-alt"></i>
                Fecha de Nacimiento *
            </label>
            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required>
        </div>

        <div class="btn-group" style="justify-content: flex-start;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i>
                <span>Guardar Paciente</span>
            </button>
            <a href="index.php" class="btn btn-secondary">
                <i class="fas fa-times"></i>
                <span>Cancelar</span>
            </a>
        </div>
    </form>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>