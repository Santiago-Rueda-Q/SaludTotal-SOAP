<?php
/**
 * Vista Listar Pacientes - RF-08
 * Muestra el listado completo de pacientes con opciones de editar y eliminar
 */

$pageTitle = 'Listar Pacientes';
$breadcrumbs = [
    ['name' => 'Lista de Pacientes']
];
include_once __DIR__ . '/partials/header.php';
include_once __DIR__ . '/partials/navbar.php';
?>

<div class="container">
    
    <div class="card">
        <div class="card-header">
            <h2><i class="fas fa-list"></i> Lista de Pacientes</h2>
        </div>

        <!-- Barra de búsqueda -->
        <div class="search-box">
            <div class="search-input-group">
                <input type="text" 
                       id="search-input" 
                       class="search-input" 
                       placeholder="Buscar por cédula, nombres o apellidos..."
                       onkeyup="filtrarTabla()">
                <button class="search-btn" onclick="cargarPacientes()">
                    <i class="fas fa-sync"></i> Recargar
                </button>
            </div>
        </div>

        <!-- Contador de resultados -->
        <div style="margin: 15px 0; color: #696a69; font-weight: 500;">
            <i class="fas fa-info-circle"></i> 
            Total de pacientes: <span id="total-count">0</span>
            <span id="filtered-count" style="display: none;">
                | Mostrando: <span id="filtered-number">0</span>
            </span>
        </div>

        <!-- Tabla de pacientes -->
        <div class="table-responsive">
            <table id="tabla-pacientes">
                <thead>
                    <tr>
                        <th>Cédula</th>
                        <th>Nombres</th>
                        <th>Apellidos</th>
                        <th>Género</th>
                        <th>Edad</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th style="text-align: center;">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tbody-pacientes">
                    <!-- Se llenará dinámicamente -->
                </tbody>
            </table>
        </div>

        <!-- Loading state -->
        <div id="loading-state" style="display: none; text-align: center; padding: 40px;">
            <div class="spinner"></div>
            <p style="color: #696a69; margin-top: 15px;">Cargando pacientes...</p>
        </div>

        <!-- Empty state -->
        <div id="empty-state" style="display: none;" class="empty-state">
            <i class="fas fa-users" style="font-size: 64px; color: #e0e0e0; margin-bottom: 20px;"></i>
            <h3>No hay pacientes registrados</h3>
            <p>Comienza registrando tu primer paciente</p>
            <a href="<?php echo route('pacientes.crear'); ?>" class="btn btn-success" style="margin-top: 20px;">
                <i class="fas fa-plus"></i> Registrar Paciente
            </a>
        </div>

        <!-- No results state -->
        <div id="no-results-state" style="display: none;" class="empty-state">
            <i class="fas fa-search" style="font-size: 64px; color: #e0e0e0; margin-bottom: 20px;"></i>
            <h3>No se encontraron resultados</h3>
            <p>Intenta con otros términos de búsqueda</p>
        </div>

        <!-- Botón volver -->
        <div style="margin-top: 20px;">
            <a href="<?php echo route('home'); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver al Inicio
            </a>
        </div>

    </div>

</div>

<script>
let pacientesData = [];

// Cargar pacientes al inicio
document.addEventListener('DOMContentLoaded', function() {
    cargarPacientes();
});

// Cargar lista de pacientes
async function cargarPacientes() {
    const tbody = document.getElementById('tbody-pacientes');
    const loadingState = document.getElementById('loading-state');
    const emptyState = document.getElementById('empty-state');
    const table = document.getElementById('tabla-pacientes');
    
    // Mostrar loading
    tbody.style.display = 'none';
    emptyState.style.display = 'none';
    loadingState.style.display = 'block';
    table.style.display = 'none';
    
    try {
        const response = await fetchAPI('listar');
        
        if (response.exito) {
            pacientesData = response.datos || [];
            
            if (pacientesData.length === 0) {
                // No hay pacientes
                loadingState.style.display = 'none';
                emptyState.style.display = 'block';
                document.getElementById('total-count').textContent = '0';
            } else {
                // Renderizar tabla
                renderizarTabla(pacientesData);
                loadingState.style.display = 'none';
                tbody.style.display = '';
                table.style.display = 'table';
                document.getElementById('total-count').textContent = pacientesData.length;
            }
        } else {
            loadingState.style.display = 'none';
            showError(response.mensaje || 'Error al cargar pacientes');
        }
        
    } catch (error) {
        loadingState.style.display = 'none';
        showError('Error de conexión: ' + error.message);
    }
}

// Renderizar tabla con datos
function renderizarTabla(pacientes) {
    const tbody = document.getElementById('tbody-pacientes');
    tbody.innerHTML = '';
    
    if (pacientes.length === 0) {
        document.getElementById('no-results-state').style.display = 'block';
        document.getElementById('tabla-pacientes').style.display = 'none';
        return;
    }
    
    document.getElementById('no-results-state').style.display = 'none';
    document.getElementById('tabla-pacientes').style.display = 'table';
    
    pacientes.forEach(paciente => {
        const edad = SaludTotal.calculateAge(paciente.fecha_nacimiento);
        const generoIcon = SaludTotal.getGenderIcon(paciente.genero);
        const generoText = SaludTotal.getGenderText(paciente.genero);
        
        const row = document.createElement('tr');
        row.innerHTML = `
            <td><strong>${escapeHtml(paciente.cedula)}</strong></td>
            <td>${escapeHtml(paciente.nombres)}</td>
            <td>${escapeHtml(paciente.apellidos)}</td>
            <td>
                <span class="tooltip">
                    ${generoIcon}
                    <span class="tooltiptext">${generoText}</span>
                </span>
            </td>
            <td>${edad} años</td>
            <td>${paciente.telefono ? SaludTotal.formatPhone(paciente.telefono) : '-'}</td>
            <td>${paciente.email || '-'}</td>
            <td style="text-align: center;">
                <div class="action-buttons">
                    <button class="btn btn-sm btn-primary" 
                            onclick="editarPaciente('${escapeHtml(paciente.cedula)}')"
                            title="Editar">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger" 
                            onclick="confirmarEliminar('${escapeHtml(paciente.cedula)}', '${escapeHtml(paciente.nombres)} ${escapeHtml(paciente.apellidos)}')"
                            title="Eliminar">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        `;
        tbody.appendChild(row);
    });
}

// Filtrar tabla
function filtrarTabla() {
    const searchTerm = document.getElementById('search-input').value.toLowerCase();
    
    if (!searchTerm) {
        renderizarTabla(pacientesData);
        document.getElementById('filtered-count').style.display = 'none';
        return;
    }
    
    const filtered = pacientesData.filter(paciente => {
        const searchText = `${paciente.cedula} ${paciente.nombres} ${paciente.apellidos}`.toLowerCase();
        return searchText.includes(searchTerm);
    });
    
    renderizarTabla(filtered);
    
    document.getElementById('filtered-count').style.display = 'inline';
    document.getElementById('filtered-number').textContent = filtered.length;
}

// Editar paciente - RF-09
function editarPaciente(cedula) {
    window.location.href = `/pacientes/editar?cedula=${cedula}`;
}

// Confirmar eliminación - RF-10
function confirmarEliminar(cedula, nombre) {
    showConfirm(
        `¿Está seguro de que desea eliminar al paciente <strong>${nombre}</strong>?<br><br>
        <small style="color: #dc3545;">Esta acción no se puede deshacer.</small>`,
        () => eliminarPaciente(cedula)
    );
}

// Eliminar paciente
async function eliminarPaciente(cedula) {
    const loading = showLoadingModal('Eliminando paciente...');
    
    try {
        const response = await fetchAPI('eliminar', { cedula: cedula }, 'POST');
        
        hideLoadingModal();
        
        if (response.exito) {
            showSuccess(response.mensaje || 'Paciente eliminado exitosamente');
            
            // Recargar lista después de 1 segundo
            setTimeout(() => {
                cargarPacientes();
            }, 1000);
            
        } else {
            showError(response.mensaje || 'Error al eliminar el paciente');
        }
        
    } catch (error) {
        hideLoadingModal();
        showError('Error de conexión: ' + error.message);
    }
}

// Escape HTML para prevenir XSS
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Búsqueda con debounce
const debouncedFilter = SaludTotal.debounce(filtrarTabla, 300);
document.getElementById('search-input').addEventListener('keyup', debouncedFilter);
</script>

<?php include_once __DIR__ . '/partials/footer.php'; ?>