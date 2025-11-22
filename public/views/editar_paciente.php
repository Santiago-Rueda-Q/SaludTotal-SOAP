<?php
/**
 * Vista Editar Paciente - RF-09
 * Formulario para actualizar datos de un paciente existente
 */

$pageTitle = 'Editar Paciente';
$breadcrumbs = [
    ['name' => 'Lista de Pacientes', 'url' => 'listar_pacientes.php'],
    ['name' => 'Editar Paciente']
];
include_once __DIR__ . '/partials/header.php';
include_once __DIR__ . '/partials/navbar.php';
?>

<div class="container">
    
    <div class="card">
        <div class="card-header">
            <h2><i class="fas fa-user-edit"></i> Editar Paciente</h2>
        </div>

        <!-- Loading state -->
        <div id="loading-state" style="display: block; text-align: center; padding: 40px;">
            <div class="spinner"></div>
            <p style="color: #696a69; margin-top: 15px;">Cargando información del paciente...</p>
        </div>

        <!-- Error state -->
        <div id="error-state" style="display: none;" class="empty-state">
            <i class="fas fa-exclamation-triangle" style="font-size: 64px; color: #dc3545; margin-bottom: 20px;"></i>
            <h3>Paciente no encontrado</h3>
            <p id="error-message">No se pudo cargar la información del paciente</p>
            <div style="margin-top: 20px;">
                <a href="listar_pacientes.php" class="btn btn-primary">
                    <i class="fas fa-list"></i> Ver Lista de Pacientes
                </a>
                <a href="index.php" class="btn btn-secondary">
                    <i class="fas fa-home"></i> Ir al Inicio
                </a>
            </div>
        </div>

        <!-- Formulario -->
        <form id="form-editar-paciente" style="display: none;" onsubmit="return handleSubmit(event)">
            
            <!-- Información del Paciente -->
            <div class="patient-info" style="margin-bottom: 25px;">
                <h3 style="color: #39aaa7; margin-bottom: 15px;">
                    <i class="fas fa-id-badge"></i> Información Actual
                </h3>
                <div class="patient-info-row">
                    <div class="patient-info-item">
                        <div class="patient-info-label">Cédula</div>
                        <div class="patient-info-value" id="display-cedula">-</div>
                    </div>
                    <div class="patient-info-item">
                        <div class="patient-info-label">Fecha de Registro</div>
                        <div class="patient-info-value" id="display-fecha">-</div>
                    </div>
                    <div class="patient-info-item">
                        <div class="patient-info-label">Estado</div>
                        <div class="patient-info-value">
                            <span class="badge badge-success">Activo</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Campo oculto para cédula -->
            <input type="hidden" id="cedula" name="cedula">

            <!-- Información Personal -->
            <h3 style="color: #39aaa7; margin: 20px 0 15px; border-bottom: 2px solid #39aaa7; padding-bottom: 10px;">
                <i class="fas fa-user"></i> Información Personal
            </h3>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="nombres">
                        Nombres <span class="required">*</span>
                    </label>
                    <input type="text" 
                           id="nombres" 
                           name="nombres" 
                           class="form-control" 
                           required
                           maxlength="100">
                </div>

                <div class="form-group">
                    <label for="apellidos">
                        Apellidos <span class="required">*</span>
                    </label>
                    <input type="text" 
                           id="apellidos" 
                           name="apellidos" 
                           class="form-control" 
                           required
                           maxlength="100">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="genero">
                        Género <span class="required">*</span>
                    </label>
                    <select id="genero" name="genero" class="form-control" required>
                        <option value="">Seleccione...</option>
                        <option value="M">Masculino</option>
                        <option value="F">Femenino</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="fecha_nacimiento">
                        Fecha de Nacimiento <span class="required">*</span>
                    </label>
                    <input type="date" 
                           id="fecha_nacimiento" 
                           name="fecha_nacimiento" 
                           class="form-control" 
                           required>
                    <small id="edad-display" style="color: #39aaa7; font-weight: 500;"></small>
                </div>
            </div>

            <!-- Información de Contacto -->
            <h3 style="color: #9bc352; margin: 30px 0 15px; border-bottom: 2px solid #9bc352; padding-bottom: 10px;">
                <i class="fas fa-phone"></i> Información de Contacto
            </h3>

            <div class="form-row">
                <div class="form-group">
                    <label for="telefono">Teléfono</label>
                    <div style="position: relative;">
                        <input type="text" 
                               id="telefono" 
                               name="telefono" 
                               class="form-control" 
                               placeholder="Ej: 3001234567"
                               maxlength="15"
                               autocomplete="off">
                        <input type="hidden" id="telefono-raw" name="telefono_raw">
                    </div>
                    <small id="telefono-hint" style="color: #999;">7 o 10 dígitos (celular debe iniciar con 3)</small>
                </div>

                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           class="form-control" 
                           maxlength="100">
                </div>
            </div>

            <div class="form-group">
                <label for="direccion">Dirección</label>
                <textarea id="direccion" 
                          name="direccion" 
                          class="form-control" 
                          rows="3" 
                          maxlength="255"></textarea>
            </div>

            <!-- Botones -->
            <div class="btn-group">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
                <button type="button" class="btn btn-secondary" onclick="cancelarEdicion()">
                    <i class="fas fa-times"></i> Cancelar
                </button>
                <button type="button" class="btn btn-danger" onclick="confirmarEliminar()">
                    <i class="fas fa-trash"></i> Eliminar Paciente
                </button>
            </div>

        </form>

        <!-- Botón volver cuando hay error -->
        <div id="error-buttons" style="display: none; margin-top: 20px;">
            <a href="listar_pacientes.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver a la Lista
            </a>
        </div>

    </div>

</div>

<script>
let pacienteActual = null;
let telefonoInput = document.getElementById('telefono');
let telefonoRaw = document.getElementById('telefono-raw');
let telefonoHint = document.getElementById('telefono-hint');

// Formatear teléfono mientras se escribe
telefonoInput.addEventListener('input', function(e) {
    // Obtener solo números
    let value = this.value.replace(/\D/g, '');
    
    // Guardar valor sin formato
    telefonoRaw.value = value;
    
    // Limitar longitud
    if (value.length > 10) {
        value = value.substring(0, 10);
        telefonoRaw.value = value;
    }
    
    // Formatear según longitud
    let formatted = '';
    if (value.length === 0) {
        formatted = '';
        telefonoHint.style.color = '#999';
        telefonoHint.textContent = '7 o 10 dígitos (celular debe iniciar con 3)';
    } else if (value.length <= 3) {
        formatted = value;
        telefonoHint.style.color = '#999';
        telefonoHint.textContent = 'Continúa escribiendo...';
    } else if (value.length <= 6) {
        formatted = `(${value.slice(0, 3)}) ${value.slice(3)}`;
        telefonoHint.style.color = '#999';
        telefonoHint.textContent = 'Continúa escribiendo...';
    } else if (value.length === 7) {
        // Formato de 7 dígitos (fijo)
        formatted = `${value.slice(0, 3)}-${value.slice(3)}`;
        telefonoHint.style.color = '#9bc352';
        telefonoHint.textContent = '✓ Teléfono fijo válido';
    } else if (value.length === 10) {
        // Formato de 10 dígitos (celular)
        formatted = `(${value.slice(0, 3)}) ${value.slice(3, 6)}-${value.slice(6)}`;
        
        // Validar que celular empiece con 3
        if (value[0] === '3') {
            telefonoHint.style.color = '#9bc352';
            telefonoHint.textContent = '✓ Celular válido';
        } else {
            telefonoHint.style.color = '#dc3545';
            telefonoHint.textContent = '✗ Celular debe iniciar con 3';
        }
    } else {
        // Longitud intermedia
        formatted = `(${value.slice(0, 3)}) ${value.slice(3, 6)}-${value.slice(6)}`;
        telefonoHint.style.color = '#999';
        telefonoHint.textContent = 'Continúa escribiendo...';
    }
    
    // Actualizar el campo visible
    this.value = formatted;
});

// Validar al perder el foco
telefonoInput.addEventListener('blur', function() {
    let value = telefonoRaw.value;
    
    if (value && value.length !== 7 && value.length !== 10) {
        telefonoHint.style.color = '#dc3545';
        telefonoHint.textContent = '✗ Debe tener 7 o 10 dígitos';
    }
});

// Cargar datos del paciente al inicio
document.addEventListener('DOMContentLoaded', async function() {
    const urlParams = new URLSearchParams(window.location.search);
    const cedula = urlParams.get('cedula');
    
    if (!cedula) {
        mostrarError('No se especificó la cédula del paciente');
        return;
    }
    
    await cargarPaciente(cedula);
});

// Cargar información del paciente
async function cargarPaciente(cedula) {
    try {
        const response = await fetchSOAP('buscar', { cedula: cedula });
        
        if (response.exito && response.datos) {
            pacienteActual = response.datos;
            llenarFormulario(pacienteActual);
            mostrarFormulario();
        } else {
            mostrarError(response.mensaje || 'Paciente no encontrado');
        }
        
    } catch (error) {
        mostrarError('Error de conexión: ' + error.message);
    }
}

// Llenar formulario con datos del paciente
function llenarFormulario(paciente) {
    document.getElementById('cedula').value = paciente.cedula;
    document.getElementById('nombres').value = paciente.nombres;
    document.getElementById('apellidos').value = paciente.apellidos;
    document.getElementById('genero').value = paciente.genero || 'Otro';
    document.getElementById('fecha_nacimiento').value = paciente.fecha_nacimiento;
    document.getElementById('email').value = paciente.email || '';
    document.getElementById('direccion').value = paciente.direccion || '';
    
    // Llenar teléfono
    if (paciente.telefono) {
        const telefonoNumeros = paciente.telefono.replace(/\D/g, '');
        telefonoRaw.value = telefonoNumeros;
        
        // Formatear para mostrar
        if (telefonoNumeros.length === 7) {
            telefonoInput.value = `${telefonoNumeros.slice(0, 3)}-${telefonoNumeros.slice(3)}`;
        } else if (telefonoNumeros.length === 10) {
            telefonoInput.value = `(${telefonoNumeros.slice(0, 3)}) ${telefonoNumeros.slice(3, 6)}-${telefonoNumeros.slice(6)}`;
        } else {
            telefonoInput.value = telefonoNumeros;
        }
    }
    
    // Llenar información de display
    document.getElementById('display-cedula').textContent = paciente.cedula;
    
    if (paciente.created_at) {
        const fecha = new Date(paciente.created_at);
        document.getElementById('display-fecha').textContent = fecha.toLocaleDateString('es-ES');
    }
    
    // Calcular edad
    calcularEdad();
}

// Mostrar formulario
function mostrarFormulario() {
    document.getElementById('loading-state').style.display = 'none';
    document.getElementById('error-state').style.display = 'none';
    document.getElementById('form-editar-paciente').style.display = 'block';
}

// Mostrar error
function mostrarError(mensaje) {
    document.getElementById('loading-state').style.display = 'none';
    document.getElementById('error-state').style.display = 'block';
    document.getElementById('error-message').textContent = mensaje;
    document.getElementById('error-buttons').style.display = 'block';
}

// Calcular edad
function calcularEdad() {
    const fecha = document.getElementById('fecha_nacimiento').value;
    if (fecha) {
        const edad = SaludTotal.calculateAge(fecha);
        document.getElementById('edad-display').textContent = `Edad: ${edad} años`;
    }
}

// Listener para cambio de fecha
document.getElementById('fecha_nacimiento').addEventListener('change', calcularEdad);

// Capitalizar nombres
document.getElementById('nombres').addEventListener('blur', function() {
    this.value = SaludTotal.capitalizeWords(this.value);
});

document.getElementById('apellidos').addEventListener('blur', function() {
    this.value = SaludTotal.capitalizeWords(this.value);
});

// Cancelar edición
function cancelarEdicion() {
    showConfirm(
        '¿Está seguro de que desea cancelar? Los cambios no guardados se perderán.',
        () => {
            window.location.href = 'listar_pacientes.php';
        }
    );
}

// Confirmar eliminación
function confirmarEliminar() {
    if (!pacienteActual) return;
    
    showConfirm(
        `¿Está seguro de que desea eliminar al paciente <strong>${pacienteActual.nombres} ${pacienteActual.apellidos}</strong>?<br><br>
        <small style="color: #dc3545;">Esta acción no se puede deshacer.</small>`,
        async () => {
            await eliminarPaciente(pacienteActual.cedula);
        }
    );
}

// Eliminar paciente
async function eliminarPaciente(cedula) {
    const loading = showLoadingModal('Eliminando paciente...');
    
    try {
        const response = await fetchSOAP('eliminar', { cedula: cedula }, 'POST');
        
        hideLoadingModal();
        
        if (response.exito) {
            showSuccess('Paciente eliminado exitosamente');
            setTimeout(() => {
                window.location.href = 'listar_pacientes.php';
            }, 1500);
        } else {
            showError(response.mensaje || 'Error al eliminar el paciente');
        }
        
    } catch (error) {
        hideLoadingModal();
        showError('Error de conexión: ' + error.message);
    }
}

// Manejar envío del formulario
async function handleSubmit(event) {
    event.preventDefault();
    
    // Validar formulario
    if (!SaludTotal.validateForm('form-editar-paciente')) {
        showError('Por favor complete todos los campos requeridos');
        return false;
    }
    
    // Obtener datos del formulario
    const formData = new FormData(event.target);
    const datos = Object.fromEntries(formData.entries());
    
    // Usar el teléfono sin formato (solo números)
    datos.telefono = telefonoRaw.value;
    
    // Validaciones adicionales
    if (datos.telefono && !SaludTotal.validatePhone(datos.telefono)) {
        showError('Número de teléfono inválido');
        return false;
    }
    
    if (datos.email && !SaludTotal.validateEmail(datos.email)) {
        showError('Correo electrónico inválido');
        return false;
    }
    
    // Mostrar loading
    const loading = showLoadingModal('Actualizando paciente...');
    
    try {
        const response = await fetchSOAP('actualizar', datos, 'POST');
        
        hideLoadingModal();
        
        if (response.exito) {
            showSuccess(response.mensaje || 'Paciente actualizado exitosamente');
            
            // Actualizar datos locales
            pacienteActual = response.datos;
            
            // Preguntar si desea volver a la lista
            setTimeout(() => {
                showConfirm(
                    '¿Desea volver al listado de pacientes?',
                    () => {
                        window.location.href = 'listar_pacientes.php';
                    }
                );
            }, 1500);
            
        } else {
            showError(response.mensaje || 'Error al actualizar el paciente');
        }
        
    } catch (error) {
        hideLoadingModal();
        showError('Error de conexión: ' + error.message);
    }
    
    return false;
}
</script>

<?php include_once __DIR__ . '/partials/footer.php'; ?>
