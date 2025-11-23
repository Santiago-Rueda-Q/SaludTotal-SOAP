<?php require __DIR__ . '/partials/header.php'; ?>

<div class="main-content">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px; flex-wrap: wrap; gap: 16px;">
        <div>
            <h2>
                <i class="fas fa-users"></i>
                Lista de Pacientes
            </h2>
            <p style="color: var(--text-secondary);">Gestione todos los pacientes registrados en el sistema</p>
        </div>
        <a href="index.php?action=crear_paciente" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i>
            <span>Nuevo Paciente</span>
        </a>
    </div>

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

    <?php if (empty($pacientes)): ?>
        <div style="text-align: center; padding: 64px 24px; background: var(--bg-secondary); border-radius: 16px;">
            <div style="font-size: 64px; margin-bottom: 16px; color: var(--text-light);">
                <i class="fas fa-user-slash"></i>
            </div>
            <h3 style="color: var(--text-secondary);">No hay pacientes registrados</h3>
            <p style="color: var(--text-secondary); margin-bottom: 24px;">Comience agregando su primer paciente al sistema</p>
            <a href="index.php?action=crear_paciente" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i>
                <span>Registrar Primer Paciente</span>
            </a>
        </div>
    <?php else: ?>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th><i class="fas fa-id-card"></i> Cédula</th>
                        <th><i class="fas fa-user"></i> Nombres</th>
                        <th><i class="fas fa-user-tag"></i> Apellidos</th>
                        <th><i class="fas fa-phone"></i> Teléfono</th>
                        <th><i class="fas fa-calendar-alt"></i> Fecha Nacimiento</th>
                        <th style="text-align: center;"><i class="fas fa-cog"></i> Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pacientes as $paciente): ?>
                        <tr>
                            <td><span class="badge badge-primary"><?php echo htmlspecialchars($paciente['cedula']); ?></span></td>
                            <td style="font-weight: 600;"><?php echo htmlspecialchars($paciente['nombres']); ?></td>
                            <td style="font-weight: 600;"><?php echo htmlspecialchars($paciente['apellidos']); ?></td>
                            <td><?php echo htmlspecialchars($paciente['telefono']); ?></td>
                            <td><?php echo htmlspecialchars($paciente['fecha_nacimiento']); ?></td>
                            <td style="text-align: center;">
                                <a href="index.php?action=editar_paciente&cedula=<?php echo $paciente['cedula']; ?>" 
                                   class="btn btn-secondary" 
                                   style="padding: 6px 12px; font-size: 14px; margin-right: 8px;">
                                    <i class="fas fa-edit"></i>
                                    <span>Editar</span>
                                </a>
                                <button 
                                   onclick="confirmDeletePatient('<?php echo htmlspecialchars($paciente['cedula']); ?>', '<?php echo htmlspecialchars($paciente['nombres'] . ' ' . $paciente['apellidos']); ?>')"
                                   class="btn btn-danger" 
                                   style="padding: 6px 12px; font-size: 14px;">
                                    <i class="fas fa-trash-alt"></i>
                                    <span>Eliminar</span>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div style="margin-top: 24px; text-align: center; color: var(--text-secondary);">
            <p>
                <i class="fas fa-info-circle"></i>
                Total de pacientes: <strong><?php echo count($pacientes); ?></strong>
            </p>
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>