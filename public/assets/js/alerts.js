/**
 * SaludTotal - Sistema de Alertas
 * Manejo de notificaciones y confirmaciones
 */

/**
 * Mostrar alerta
 */
function showAlert(message, type = 'info', duration = 3000) {
    // Crear contenedor si no existe
    let container = document.getElementById('alert-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'alert-container';
        container.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 10000;
            max-width: 400px;
        `;
        document.body.appendChild(container);
    }

    // Crear alerta
    const alert = document.createElement('div');
    alert.className = `alert alert-${type} slide-up`;
    
    // Estilos específicos para cada tipo
    const colors = {
        success: { bg: '#d4edda', border: '#9bc352', text: '#155724', icon: '✓' },
        danger: { bg: '#f8d7da', border: '#dc3545', text: '#721c24', icon: '✗' },
        warning: { bg: '#fff3cd', border: '#ffc107', text: '#856404', icon: '⚠' },
        info: { bg: '#d1ecf1', border: '#39aaa7', text: '#0c5460', icon: 'ℹ' }
    };
    
    const color = colors[type] || colors.info;
    
    alert.style.cssText = `
        background: ${color.bg};
        border: 2px solid ${color.border};
        color: ${color.text};
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        animation: slideInRight 0.3s ease;
        font-weight: 500;
    `;
    
    alert.innerHTML = `
        <span style="font-size: 24px; font-weight: bold;">${color.icon}</span>
        <span style="flex: 1;">${message}</span>
        <button onclick="this.parentElement.remove()" style="
            background: transparent;
            border: none;
            color: ${color.text};
            font-size: 20px;
            cursor: pointer;
            padding: 0;
            width: 24px;
            height: 24px;
            opacity: 0.7;
        ">×</button>
    `;
    
    container.appendChild(alert);
    
    // Auto-remover después de la duración especificada
    if (duration > 0) {
        setTimeout(() => {
            alert.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => alert.remove(), 300);
        }, duration);
    }
    
    return alert;
}

/**
 * Mostrar alerta de éxito
 */
function showSuccess(message, duration = 3000) {
    return showAlert(message, 'success', duration);
}

/**
 * Mostrar alerta de error
 */
function showError(message, duration = 5000) {
    return showAlert(message, 'danger', duration);
}

/**
 * Mostrar alerta de advertencia
 */
function showWarning(message, duration = 4000) {
    return showAlert(message, 'warning', duration);
}

/**
 * Mostrar alerta de información
 */
function showInfo(message, duration = 3000) {
    return showAlert(message, 'info', duration);
}

/**
 * Mostrar diálogo de confirmación
 */
function showConfirm(message, onConfirm, onCancel = null) {
    // Crear overlay
    const overlay = document.createElement('div');
    overlay.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 10000;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: fadeIn 0.3s ease;
    `;
    
    // Crear modal
    const modal = document.createElement('div');
    modal.style.cssText = `
        background: white;
        border-radius: 10px;
        padding: 0;
        max-width: 500px;
        width: 90%;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        animation: slideUp 0.3s ease;
    `;
    
    modal.innerHTML = `
        <div style="
            background: #39aaa7;
            color: white;
            padding: 20px;
            border-radius: 10px 10px 0 0;
            font-size: 20px;
            font-weight: 600;
        ">
            ⚠️ Confirmación
        </div>
        <div style="padding: 30px; font-size: 16px; color: #696a69; line-height: 1.6;">
            ${message}
        </div>
        <div style="
            padding: 20px;
            border-top: 1px solid #e0e0e0;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        ">
            <button id="confirm-cancel" style="
                padding: 10px 24px;
                background: #696a69;
                color: white;
                border: none;
                border-radius: 6px;
                cursor: pointer;
                font-size: 15px;
                font-weight: 500;
            ">Cancelar</button>
            <button id="confirm-ok" style="
                padding: 10px 24px;
                background: #39aaa7;
                color: white;
                border: none;
                border-radius: 6px;
                cursor: pointer;
                font-size: 15px;
                font-weight: 500;
            ">Confirmar</button>
        </div>
    `;
    
    overlay.appendChild(modal);
    document.body.appendChild(overlay);
    
    // Handlers
    const btnOk = modal.querySelector('#confirm-ok');
    const btnCancel = modal.querySelector('#confirm-cancel');
    
    const closeModal = () => {
        overlay.style.animation = 'fadeOut 0.3s ease';
        setTimeout(() => overlay.remove(), 300);
    };
    
    btnOk.addEventListener('click', () => {
        closeModal();
        if (onConfirm) onConfirm();
    });
    
    btnCancel.addEventListener('click', () => {
        closeModal();
        if (onCancel) onCancel();
    });
    
    overlay.addEventListener('click', (e) => {
        if (e.target === overlay) {
            closeModal();
            if (onCancel) onCancel();
        }
    });
    
    // Focus en botón OK
    btnOk.focus();
}

/**
 * Mostrar modal de carga
 */
function showLoadingModal(message = 'Procesando...') {
    const overlay = document.createElement('div');
    overlay.id = 'loading-modal';
    overlay.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 10000;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: fadeIn 0.3s ease;
    `;
    
    overlay.innerHTML = `
        <div style="
            background: white;
            border-radius: 10px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        ">
            <div class="spinner" style="
                border: 4px solid #e0e0e0;
                border-top: 4px solid #39aaa7;
                border-radius: 50%;
                width: 50px;
                height: 50px;
                animation: spin 1s linear infinite;
                margin: 0 auto 20px;
            "></div>
            <div style="color: #696a69; font-size: 16px; font-weight: 500;">
                ${message}
            </div>
        </div>
    `;
    
    document.body.appendChild(overlay);
    return overlay;
}

/**
 * Ocultar modal de carga
 */
function hideLoadingModal() {
    const modal = document.getElementById('loading-modal');
    if (modal) {
        modal.style.animation = 'fadeOut 0.3s ease';
        setTimeout(() => modal.remove(), 300);
    }
}

/**
 * Mostrar toast (notificación pequeña)
 */
function showToast(message, type = 'info', duration = 2000) {
    const toast = document.createElement('div');
    toast.style.cssText = `
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: ${type === 'success' ? '#9bc352' : '#39aaa7'};
        color: white;
        padding: 12px 20px;
        border-radius: 6px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        z-index: 10000;
        animation: slideUp 0.3s ease;
        font-weight: 500;
        max-width: 300px;
    `;
    
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.animation = 'fadeOut 0.3s ease';
        setTimeout(() => toast.remove(), 300);
    }, duration);
}

// Agregar estilos de animación al documento
if (!document.getElementById('alert-animations')) {
    const style = document.createElement('style');
    style.id = 'alert-animations';
    style.textContent = `
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }
        
        @keyframes slideUp {
            from {
                transform: translateY(20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    `;
    document.head.appendChild(style);
}

// Exponer funciones globalmente
window.showAlert = showAlert;
window.showSuccess = showSuccess;
window.showError = showError;
window.showWarning = showWarning;
window.showInfo = showInfo;
window.showConfirm = showConfirm;
window.showLoadingModal = showLoadingModal;
window.hideLoadingModal = hideLoadingModal;
window.showToast = showToast;