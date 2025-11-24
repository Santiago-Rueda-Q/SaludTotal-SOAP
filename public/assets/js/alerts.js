/**
 * Sistema de Alertas y Diálogos para SaludTotal
 * Incluye DialogBox personalizado y sistema de notificaciones
 */

// ==================== CLASE DIALOGBOX ====================
class DialogBox {
    constructor() {
        this.overlay = null;
    }

    /**
     * Muestra un diálogo personalizado
     * @param {Object} options - Opciones del diálogo
     */
    show(options) {
        const {
            title = 'Confirmar acción',
            message = '¿Está seguro de realizar esta acción?',
            type = 'warning',
            confirmText = 'Confirmar',
            cancelText = 'Cancelar',
            onConfirm = () => { },
            onCancel = () => { }
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

        // Enfocar el botón de confirmar
        setTimeout(() => confirmBtn.focus(), 100);
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

// ==================== CLASE NOTIFICATIONSYSTEM ====================
class NotificationSystem {
    constructor() {
        this.container = this.createContainer();
    }

    createContainer() {
        let container = document.getElementById('notification-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'notification-container';
            container.className = 'notification-container';
            document.body.appendChild(container);
        }
        return container;
    }

    /**
     * Muestra una notificación
     * @param {Object} options - Opciones de la notificación
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
        if (!notification.parentNode) return;

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

// ==================== INSTANCIAS GLOBALES ====================
const dialog = new DialogBox();
const notify = new NotificationSystem();

// ==================== FUNCIONES ESPECÍFICAS ====================

/**
 * Confirma la eliminación de un paciente con diálogo personalizado
 * @param {string} cedula - Cédula del paciente
 * @param {string} nombre - Nombre completo del paciente
 */
function confirmDeletePatient(cedula, nombre) {
    dialog.show({
        title: 'Eliminar Paciente',
        message: `
            <p>¿Está seguro de que desea eliminar al paciente?</p>
            <p style="margin-top: 12px;"><strong>${nombre}</strong></p>
            <p style="margin-top: 8px; color: var(--text-secondary);">Cédula: <strong>${cedula}</strong></p>
            <p style="margin-top: 16px; padding: 12px; background: rgba(220, 53, 69, 0.1); border-radius: 8px; color: var(--danger); font-size: 14px;">
                <i class="fas fa-exclamation-triangle"></i>
                Esta acción no se puede deshacer
            </p>
        `,
        type: 'danger',
        confirmText: 'Sí, eliminar',
        cancelText: 'Cancelar',
        onConfirm: () => {
            // Mostrar notificación de procesamiento
            notify.info('Eliminando paciente...', 'Procesando');

            // Redirigir a la acción de eliminar
            window.location.href = `index.php?action=eliminar_paciente&cedula=${encodeURIComponent(cedula)}`;
        },
        onCancel: () => {
            notify.info('Operación cancelada', 'Información');
        }
    });
}

/**
 * Función legacy para compatibilidad
 * @param {string} cedula - Cédula del paciente
 * @returns {boolean}
 */
function confirmDelete(cedula) {
    return confirm('¿Está seguro de que desea eliminar al paciente con cédula ' + cedula + '?');
}

/**
 * Muestra una alerta de éxito
 */
function showSuccess(message, title = 'Éxito') {
    notify.success(message, title);
}

/**
 * Muestra una alerta de error
 */
function showError(message, title = 'Error') {
    notify.error(message, title);
}

/**
 * Muestra una alerta de advertencia
 */
function showWarning(message, title = 'Advertencia') {
    notify.warning(message, title);
}

/**
 * Muestra una alerta informativa
 */
function showInfo(message, title = 'Información') {
    notify.info(message, title);
}

/**
 * Diálogo de confirmación genérico
 * @param {Object} options - Opciones del diálogo
 * @returns {Promise}
 */
function confirmAction(options) {
    return new Promise((resolve) => {
        dialog.show({
            ...options,
            onConfirm: () => resolve(true),
            onCancel: () => resolve(false)
        });
    });
}

// ==================== EXPORTAR PARA USO GLOBAL ====================
window.dialog = dialog;
window.notify = notify;
window.confirmDeletePatient = confirmDeletePatient;
window.confirmDelete = confirmDelete;
window.showSuccess = showSuccess;
window.showError = showError;
window.showWarning = showWarning;
window.showInfo = showInfo;
window.confirmAction = confirmAction;

// Log de inicialización
console.log('%c✅ Sistema de alertas SaludTotal cargado', 'color: #4BA89C; font-weight: bold;');