<?php
/**
 * Vista Crear Paciente - RF-07
 * Formulario para registrar un nuevo paciente
 */

$pageTitle = 'Registrar Paciente';
$breadcrumbs = [
    ['name' => 'Registrar Paciente']
];
include_once __DIR__ . '/partials/header.php';
include_once __DIR__ . '/partials/navbar.php';
?>

<div class="container">
    
    <div class="card">
        <div class="card-header">
            <h2><i class="fas fa-user-plus"></i> Registrar Nuevo Paciente</h2>
        </div>

        <form id="form-crear-paciente" onsubmit="return handleSubmit(event)">
            
            <!-- Información Personal -->
            <h3 style="color: #39aaa7; margin: 20px 0 15px; border-bottom: 2px solid #39aaa7; padding-bottom: 10px;">
                <i class="fas fa-id-card"></i> Información Personal
            </h3>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="cedula">
                        Cédula <span class="required">*</span>
                    </label>
                    <input type="text" 
                           id="cedula" 
                           name="cedula" 
                           class="form-control" 
                           placeholder="Ej: 1234567890"
                           required
                           maxlength="10"
                           pattern="[0-9]{6,10}">
                    <small style="color: #999;">Solo números, entre 6 y 10 dígitos</small>
                </div>

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
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="nombres">
                        Nombres <span class="required">*</span>
                    </label>
                    <input type="text" 
                           id="nombres" 
                           name="nombres" 
                           class="form-control" 
                           placeholder="Ej: Juan Carlos"
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
                           placeholder="Ej: Pérez Gómez"
                           required
                           maxlength="100">
                </div>
            </div>

            <div class="form-row">
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
                           placeholder="correo@ejemplo.com"
                           maxlength="100">
                </div>
            </div>

            <div class="form-group">
                <label for="direccion">Dirección</label>
                <textarea id="direccion" 
                          name="direccion" 
                          class="form-control" 
                          rows="3" 
                          placeholder="Ej: Calle 123 #45-67, Barrio Centro"
                          maxlength="255"></textarea>
            </div>

            <!-- Botones -->
            <div class="btn-group">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Registrar Paciente
                </button>
                <button type="button" class="btn btn-secondary" onclick="limpiarFormulario()">
                    <i class="fas fa-eraser"></i> Limpiar
                </button>
                <a href="<?php echo route('home'); ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver al Inicio
                </a>
            </div>

        </form>
    </div>

</div>

<script>
// Variables para el teléfono
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

// Calcular edad al cambiar fecha de nacimiento
document.getElementById('fecha_nacimiento').addEventListener('change', function() {
    if (this.value) {
        const edad = SaludTotal.calculateAge(this.value);
        document.getElementById('edad-display').textContent = `Edad: ${edad} años`;
    } else {
        document.getElementById('edad-display').textContent = '';
    }
});

// Capitalizar nombres y apellidos automáticamente
document.getElementById('nombres').addEventListener('blur', function() {
    this.value = SaludTotal.capitalizeWords(this.value);
});

document.getElementById('apellidos').addEventListener('blur', function() {
    this.value = SaludTotal.capitalizeWords(this.value);
});

// Validar cédula en tiempo real
document.getElementById('cedula').addEventListener('input', function() {
    this.value = this.value.replace(/\D/g, '');
});

// Limpiar formulario
function limpiarFormulario() {
    document.getElementById('form-crear-paciente').reset();
    document.getElementById('edad-display').textContent = '';
    telefonoRaw.value = '';
    telefonoHint.style.color = '#999';
    telefonoHint.textContent = '7 o 10 dígitos (celular debe iniciar con 3)';
    showInfo('Formulario limpiado');
}

// Manejar envío del formulario
async function handleSubmit(event) {
    event.preventDefault();
    
    // Validar formulario
    if (!SaludTotal.validateForm('form-crear-paciente')) {
        showError('Por favor complete todos los campos requeridos');
        return false;
    }
    
    // Obtener datos del formulario
    const formData = new FormData(event.target);
    const datos = Object.fromEntries(formData.entries());
    
    // Usar el teléfono sin formato (solo números)
    datos.telefono = telefonoRaw.value;
    
    // Validaciones adicionales
    if (!SaludTotal.validateCedula(datos.cedula)) {
        showError('Número de cédula inválido (6-10 dígitos)');
        return false;
    }
    
    if (datos.telefono && !SaludTotal.validatePhone(datos.telefono)) {
        showError('Número de teléfono inválido');
        return false;
    }
    
    if (datos.email && !SaludTotal.validateEmail(datos.email)) {
        showError('Correo electrónico inválido');
        return false;
    }
    
    // Mostrar loading
    const loading = showLoadingModal('Registrando paciente...');
    
    try {
        // Enviar datos al servidor
        const response = await fetchSOAP('crear', datos, 'POST');
        
        hideLoadingModal();
        
        if (response.exito) {
            showSuccess(response.mensaje || 'Paciente registrado exitosamente');
            
            // Limpiar formulario después de 1 segundo
            setTimeout(() => {
                limpiarFormulario();
            }, 1000);
            
            // Preguntar si desea ver el listado
            setTimeout(() => {
                showConfirm(
                    '¿Desea ver el listado de pacientes?',
                    () => {
                        window.location.href = '<?php echo route('pacientes.listar'); ?>';
                    }
                );
            }, 1500);
            
        } else {
            showError(response.mensaje || 'Error al registrar el paciente');
        }
        
    } catch (error) {
        hideLoadingModal();
        showError('Error de conexión: ' + error.message);
    }
    
    return false;
}
</script>

<?php include_once __DIR__ . '/partials/footer.php'; ?>
