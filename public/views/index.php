<?php
/**
 * Vista Principal - RF-06
 * Página de inicio del sistema SaludTotal
 */

$pageTitle = 'Inicio';
include_once __DIR__ . '/partials/header.php';
include_once __DIR__ . '/partials/navbar.php';
?>

<div class="container">
    
    <!-- Hero Section -->
    <div class="hero">
        <h1><i class="fas fa-heartbeat"></i> Bienvenido a SaludTotal</h1>
        <p>Sistema integral para la gestión de pacientes de la clínica</p>
    </div>

    <!-- Estadísticas (Cards) -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-number" id="total-pacientes">0</div>
            <div class="stat-label">Total de Pacientes</div>
        </div>
        
        <div class="stat-card secondary">
            <div class="stat-icon">
                <i class="fas fa-user-plus"></i>
            </div>
            <div class="stat-number" id="pacientes-hoy">0</div>
            <div class="stat-label">Registros Hoy</div>
        </div>
        
        <div class="stat-card gray">
            <div class="stat-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="stat-number" id="pacientes-mes">0</div>
            <div class="stat-label">Registros Este Mes</div>
        </div>
    </div>

    <!-- Action Cards -->
    <div class="action-cards">
        
        <!-- Ver Pacientes -->
        <div class="action-card">
            <div class="action-card-icon">
                <i class="fas fa-list-ul"></i>
            </div>
            <div class="action-card-body">
                <h3>Ver Pacientes</h3>
                <p>Consulta el listado completo de pacientes registrados, busca por cédula y gestiona la información.</p>
                <a href="<?php echo route('pacientes.listar'); ?>" class="btn btn-primary">
                    <i class="fas fa-eye"></i> Ver Listado
                </a>
            </div>
        </div>

        <!-- Registrar Paciente -->
        <div class="action-card secondary">
            <div class="action-card-icon">
                <i class="fas fa-user-plus"></i>
            </div>
            <div class="action-card-body">
                <h3>Registrar Paciente</h3>
                <p>Añade un nuevo paciente al sistema completando el formulario con su información personal.</p>
                <a href="<?php echo route('pacientes.crear'); ?>" class="btn btn-success">
                    <i class="fas fa-plus"></i> Nuevo Registro
                </a>
            </div>
        </div>

        <!-- Buscar Paciente -->
        <div class="action-card">
            <div class="action-card-icon">
                <i class="fas fa-search"></i>
            </div>
            <div class="action-card-body">
                <h3>Buscar Paciente</h3>
                <p>Encuentra rápidamente un paciente específico ingresando su número de cédula.</p>
                <div style="margin-top: 15px;">
                    <input type="text" id="search-cedula" class="form-control" 
                           placeholder="Ingrese cédula" maxlength="10"
                           style="margin-bottom: 10px;">
                    <button onclick="buscarPaciente()" class="btn btn-primary" style="width: 100%;">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                </div>
            </div>
        </div>

    </div>

    <!-- Información del Sistema -->
    <div class="card" style="margin-top: 30px;">
        <div class="card-header">
            <h2><i class="fas fa-info-circle"></i> Información del Sistema</h2>
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
            <div>
                <h4 style="color: #39aaa7; margin-bottom: 10px;">
                    <i class="fas fa-cog"></i> Funcionalidades
                </h4>
                <ul style="color: #696a69; line-height: 2;">
                    <li>✓ Crear pacientes (RF-01)</li>
                    <li>✓ Buscar por cédula (RF-02)</li>
                    <li>✓ Listar todos (RF-03)</li>
                    <li>✓ Actualizar datos (RF-04)</li>
                    <li>✓ Eliminar registros (RF-05)</li>
                </ul>
            </div>
            <div>
                <h4 style="color: #9bc352; margin-bottom: 10px;">
                    <i class="fas fa-shield-alt"></i> Tecnología
                </h4>
                <ul style="color: #696a69; line-height: 2;">
                    <li>✓ Arquitectura SOAP</li>
                    <li>✓ Protocolo WSDL</li>
                    <li>✓ Base de datos MySQL</li>
                    <li>✓ Sincronización XML</li>
                    <li>✓ Interfaz responsiva</li>
                </ul>
            </div>
            <div>
                <h4 style="color: #696a69; margin-bottom: 10px;">
                    <i class="fas fa-user-shield"></i> Seguridad
                </h4>
                <ul style="color: #696a69; line-height: 2;">
                    <li>✓ Validación de datos</li>
                    <li>✓ Manejo de errores</li>
                    <li>✓ Logs de actividad</li>
                    <li>✓ Soft delete</li>
                    <li>✓ Transacciones seguras</li>
                </ul>
            </div>
        </div>
    </div>

</div>

<script>
// Cargar estadísticas al inicio
async function loadStatistics() {
    try {
        const response = await fetchSOAP('listar');
        
        if (response.exito) {
            const pacientes = response.datos || [];
            
            // Total de pacientes
            document.getElementById('total-pacientes').textContent = pacientes.length;
            
            // Pacientes registrados hoy
            const today = new Date().toISOString().split('T')[0];
            const hoy = pacientes.filter(p => p.created_at && p.created_at.startsWith(today));
            document.getElementById('pacientes-hoy').textContent = hoy.length;
            
            // Pacientes este mes
            const currentMonth = new Date().toISOString().substring(0, 7);
            const mes = pacientes.filter(p => p.created_at && p.created_at.startsWith(currentMonth));
            document.getElementById('pacientes-mes').textContent = mes.length;
        }
    } catch (error) {
        console.error('Error al cargar estadísticas:', error);
    }
}

// Buscar paciente desde la página principal
async function buscarPaciente() {
    const cedula = document.getElementById('search-cedula').value.trim();
    
    if (!cedula) {
        showWarning('Por favor ingrese un número de cédula');
        return;
    }
    
    if (!SaludTotal.validateCedula(cedula)) {
        showError('Número de cédula inválido');
        return;
    }
    
    const loading = showLoadingModal('Buscando paciente...');
    
    try {
        const response = await fetchSOAP('buscar', { cedula: cedula });
        
        hideLoadingModal();
        
        if (response.exito && response.datos) {
            // Redirigir a la página de edición
            window.location.href = `editar_paciente.php?cedula=${cedula}`;
        } else {
            showError(response.mensaje || 'Paciente no encontrado');
        }
    } catch (error) {
        hideLoadingModal();
        showError('Error al buscar el paciente');
    }
}

// Enter para buscar
document.getElementById('search-cedula').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        buscarPaciente();
    }
});

// Cargar estadísticas al cargar la página
document.addEventListener('DOMContentLoaded', loadStatistics);
</script>

<?php include_once __DIR__ . '/partials/footer.php'; ?>
