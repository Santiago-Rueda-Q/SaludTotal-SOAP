<?php require __DIR__ . '/partials/header.php'; ?>

<div class="main-content">
    <h2>
        <i class="fas fa-edit"></i>
        Editar Paciente
    </h2>
    <p style="color: var(--text-secondary); margin-bottom: 32px;">Actualice la información del paciente</p>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-triangle"></i>
            <span><?php echo htmlspecialchars($_GET['error']); ?></span>
        </div>
    <?php endif; ?>

    <?php if (isset($paciente)): ?>
        <form method="POST" action="index.php?action=editar_paciente&cedula=<?php echo htmlspecialchars($paciente['cedula']); ?>" id="formEditar">
            <div class="form-group">
                <label for="cedula">
                    <i class="fas fa-id-card"></i>
                    Cédula *
                </label>
                <input type="text" 
                       id="cedula" 
                       name="cedula" 
                       required 
                       readonly
                       value="<?php echo htmlspecialchars($paciente['cedula']); ?>"
                       style="background-color: #e9ecef; cursor: not-allowed;"
                       placeholder="Ej: 1234567890">
            </div>

            <div class="form-group">
                <label for="nombres">
                    <i class="fas fa-user"></i>
                    Nombres *
                </label>
                <input type="text" 
                       id="nombres" 
                       name="nombres" 
                       required 
                       value="<?php echo htmlspecialchars($paciente['nombres']); ?>"
                       placeholder="Ej: Juan Carlos">
            </div>

            <div class="form-group">
                <label for="apellidos">
                    <i class="fas fa-user-tag"></i>
                    Apellidos *
                </label>
                <input type="text" 
                       id="apellidos" 
                       name="apellidos" 
                       required 
                       value="<?php echo htmlspecialchars($paciente['apellidos']); ?>"
                       placeholder="Ej: Pérez García">
            </div>

            <div class="form-group">
                <label for="telefono">
                    <i class="fas fa-phone"></i>
                    Teléfono *
                </label>
                <input type="text" 
                       id="telefono" 
                       name="telefono" 
                       required 
                       value="<?php echo htmlspecialchars($paciente['telefono']); ?>"
                       placeholder="Ej: +57 300 123 4567">
            </div>

            <div class="form-group">
                <label for="fecha_nacimiento">
                    <i class="fas fa-calendar-alt"></i>
                    Fecha de Nacimiento *
                </label>
                <input type="date" 
                       id="fecha_nacimiento" 
                       name="fecha_nacimiento" 
                       required 
                       value="<?php echo htmlspecialchars($paciente['fecha_nacimiento']); ?>">
            </div>

            <div class="btn-group" style="justify-content: flex-start;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    <span>Actualizar Paciente</span>
                </button>
                <a href="index.php?action=listar_pacientes" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    <span>Cancelar</span>
                </a>
            </div>
        </form>
    <?php else: ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-triangle"></i>
            <span>No se encontró el paciente solicitado</span>
        </div>
        <a href="index.php?action=listar_pacientes" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i>
            <span>Volver a la lista</span>
        </a>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>