// ==================== SISTEMA DE DIÁLOGOS ====================

class DialogBox {
    constructor() {
        this.overlay = null;
    }

    /**
     * Muestra un diálogo personalizado
     * @param {Object} options - Opciones del diálogo
     * @param {string} options.title - Título del diálogo
     * @param {string} options.message - Mensaje del diálogo
     * @param {string} options.type - Tipo: 'warning', 'danger', 'success', 'info'
     * @param {string} options.confirmText - Texto del botón confirmar
     * @param {string} options.cancelText - Texto del botón cancelar
     * @param {function} options.onConfirm - Callback al confirmar
     * @param {function} options.onCancel - Callback al cancelar
     */
    show(options) {
        const {
            title = 'Confirmar acción',
            message = '¿Está seguro de realizar esta acción?',
            type = 'warning',
            confirmText = 'Confirmar',
            cancelText = 'Cancelar',
            onConfirm = () => {},
            onCancel = () => {}
        } = options;

        // Remover diálogo anterior si existe
        this.close();

        // Crear overlay
        this.overlay = document.createElement('div');
        this.overlay.className = 'dialog-overlay';

        // Crear caja del diálogo
        const dialogBox = document.createElement('div');
        dialogBox.className = 'dialog-box';

        // Iconos según el tipo
        const icons = {
            warning: 'fas fa-exclamation-triangle',
            danger: 'fas fa-exclamation-circle',
            success: 'fas fa-check-circle',
            info: 'fas fa-info-circle'
        };

        dialogBox.innerHTML = `
            <div class="dialog-header">
                <div class="dialog-icon ${type}">
                    <i class="${icons[type]}"></i>
                </div>
                <div class="dialog-title">${title}</div>
            </div>
            <div class="dialog-content">${message}</div>
            <div class="dialog-actions">
                <button class="btn btn-secondary dialog-cancel">
                    <i class="fas fa-times"></i>
                    <span>${cancelText}</span>
                </button>
                <button class="btn btn-${type === 'danger' ? 'danger' : 'primary'} dialog-confirm">
                    <i class="fas fa-check"></i>
                    <span>${confirmText}</span>
                </button>
            </div>
        `;

        this.overlay.appendChild(dialogBox);
        document.body.appendChild(this.overlay);

        // Event listeners
        const confirmBtn = dialogBox.querySelector('.dialog-confirm');
        const cancelBtn = dialogBox.querySelector('.dialog-cancel');

        confirmBtn.addEventListener('click', () => {
            onConfirm();
            this.close();
        });

        cancelBtn.addEventListener('click', () => {
            onCancel();
            this.close();
        });

        // Cerrar al hacer clic fuera del diálogo
        this.overlay.addEventListener('click', (e) => {
            if (e.target === this.overlay) {
                onCancel();
                this.close();
            }
        });

        // Cerrar con tecla ESC
        const escapeHandler = (e) => {
            if (e.key === 'Escape') {
                onCancel();
                this.close();
                document.removeEventListener('keydown', escapeHandler);
            }
        };
        document.addEventListener('keydown', escapeHandler);
    }

    close() {
        if (this.overlay && this.overlay.parentNode) {
            this.overlay.style.opacity = '0';
            setTimeout(() => {
                this.overlay.remove();
                this.overlay = null;
            }, 300);
        }
    }
}

// Instancia global del DialogBox
const dialog = new DialogBox();

// ==================== SISTEMA DE NOTIFICACIONES ====================

class NotificationSystem {
    constructor() {
        this.container = document.getElementById('notification-container');
        if (!this.container) {
            this.container = document.createElement('div');
            this.container.id = 'notification-container';
            this.container.className = 'notification-container';
            document.body.appendChild(this.container);
        }
    }

    /**
     * Muestra una notificación
     * @param {Object} options - Opciones de la notificación
     * @param {string} options.title - Título de la notificación
     * @param {string} options.message - Mensaje de la notificación
     * @param {string} options.type - Tipo: 'success', 'error', 'warning', 'info'
     * @param {number} options.duration - Duración en ms (0 = permanente)
     */
    show(options) {
        const {
            title = '',
            message = '',
            type = 'info',
            duration = 5000
        } = options;

        // Iconos según el tipo
        const icons = {
            success: 'fas fa-check-circle',
            error: 'fas fa-times-circle',
            warning: 'fas fa-exclamation-triangle',
            info: 'fas fa-info-circle'
        };

        // Crear notificación
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;

        notification.innerHTML = `
            <div class="notification-icon">
                <i class="${icons[type]}"></i>
            </div>
            <div class="notification-content">
                ${title ? `<div class="notification-title">${title}</div>` : ''}
                <div class="notification-message">${message}</div>
            </div>
            <button class="notification-close">
                <i class="fas fa-times"></i>
            </button>
        `;

        // Agregar al contenedor
        this.container.appendChild(notification);

        // Event listener para cerrar
        const closeBtn = notification.querySelector('.notification-close');
        closeBtn.addEventListener('click', () => this.close(notification));

        // Auto-cerrar si tiene duración
        if (duration > 0) {
            setTimeout(() => this.close(notification), duration);
        }

        return notification;
    }

    close(notification) {
        notification.classList.add('closing');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 300);
    }

    // Métodos de conveniencia
    success(message, title = 'Éxito') {
        return this.show({ type: 'success', title, message });
    }

    error(message, title = 'Error') {
        return this.show({ type: 'error', title, message });
    }

    warning(message, title = 'Advertencia') {
        return this.show({ type: 'warning', title, message });
    }

    info(message, title = 'Información') {
        return this.show({ type: 'info', title, message });
    }
}

// Instancia global del sistema de notificaciones
const notify = new NotificationSystem();

// ==================== FUNCIONES ESPECÍFICAS DE LA APLICACIÓN ====================

/**
 * Confirma la eliminación de un paciente
 * @param {string} cedula - Cédula del paciente
 * @param {string} nombre - Nombre completo del paciente
 */
function confirmDeletePatient(cedula, nombre) {
    dialog.show({
        title: 'Eliminar Paciente',
        message: `¿Está seguro de que desea eliminar al paciente <strong>${nombre}</strong>?<br><br>Cédula: <strong>${cedula}</strong><br><br>Esta acción no se puede deshacer.`,
        type: 'danger',
        confirmText: 'Sí, eliminar',
        cancelText: 'Cancelar',
        onConfirm: () => {
            // Redirigir a la acción de eliminar usando la cédula
            window.location.href = `index.php?action=eliminar_paciente&cedula=${cedula}`;
        }
    });
}

/**
 * Función legacy para compatibilidad
 */
function confirmDelete(cedula) {
    return confirm('¿Está seguro de que desea eliminar al paciente con cédula ' + cedula + '?');
}

// ==================== INICIALIZACIÓN ====================

document.addEventListener('DOMContentLoaded', function () {
    console.log('%c🏥 SaludTotal SOAP', 'color: #4BA89C; font-size: 20px; font-weight: bold;');
    console.log('%cSistema de gestión de pacientes cargado correctamente', 'color: #A8C956;');

    // Auto-ocultar alertas después de 5 segundos
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'all 0.5s ease';
            alert.style.opacity = '0';
            alert.style.transform = 'translateX(-20px)';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });

    // Validación de formularios
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredInputs = form.querySelectorAll('[required]');
            let isValid = true;
            const errors = [];

            requiredInputs.forEach(input => {
                if (!input.value.trim()) {
                    isValid = false;
                    input.classList.add('error');
                    input.style.animation = 'shake 0.3s';
                    
                    const label = form.querySelector(`label[for="${input.id}"]`);
                    if (label) {
                        errors.push(label.textContent.replace('*', '').trim());
                    }
                } else {
                    input.classList.remove('error');
                }
            });

            if (!isValid) {
                e.preventDefault();
                notify.error(
                    `Por favor complete los siguientes campos: ${errors.join(', ')}`,
                    'Campos requeridos'
                );
            }
        });
    });

    // Limpiar estilos de error al escribir
    const inputs = document.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            this.classList.remove('error');
        });
    });

    // Animación para las filas de la tabla
    const tableRows = document.querySelectorAll('.table tbody tr');
    tableRows.forEach((row, index) => {
        row.style.opacity = '0';
        row.style.animation = `fadeIn 0.5s ease ${index * 0.05}s forwards`;
    });

    // Formateo automático de teléfono colombiano
    const phoneInputs = document.querySelectorAll('input[type="tel"]');
    phoneInputs.forEach(input => {
        input.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            
            // Formato: +57 ### ### ####
            if (value.startsWith('57')) {
                value = value.substring(2);
            }
            
            if (value.length > 0) {
                if (value.length <= 3) {
                    value = '+57 ' + value;
                } else if (value.length <= 6) {
                    value = '+57 ' + value.slice(0, 3) + ' ' + value.slice(3);
                } else {
                    value = '+57 ' + value.slice(0, 3) + ' ' + value.slice(3, 6) + ' ' + value.slice(6, 10);
                }
            }
            
            e.target.value = value;
        });
    });

    // Animación de carga para botones de envío
    const submitButtons = document.querySelectorAll('button[type="submit"]');
    submitButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const form = this.closest('form');
            if (form && form.checkValidity()) {
                this.disabled = true;
                const originalContent = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Procesando...</span>';
                this.style.opacity = '0.7';
                
                // Restaurar después de 10 segundos si no se envió
                setTimeout(() => {
                    if (this.disabled) {
                        this.disabled = false;
                        this.innerHTML = originalContent;
                        this.style.opacity = '1';
                    }
                }, 10000);
            }
        });
    });

    // Mostrar notificación si hay mensaje en la URL
    const urlParams = new URLSearchParams(window.location.search);
    const mensaje = urlParams.get('mensaje');
    const error = urlParams.get('error');

    if (mensaje) {
        notify.success(mensaje);
    }
    if (error) {
        notify.error(error);
    }
});

// Agregar animación de shake al CSS
const style = document.createElement('style');
style.textContent = `
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-10px); }
        75% { transform: translateX(10px); }
    }
`;
document.head.appendChild(style);

// ==================== EXPORTAR PARA USO GLOBAL ====================
window.dialog = dialog;
window.notify = notify;
window.confirmDeletePatient = confirmDeletePatient;
window.confirmDelete = confirmDelete;