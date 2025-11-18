/**
 * SaludTotal - JavaScript Principal
 * Funciones globales y utilidades (Actualizado con nuevas rutas)
 */

// Configuración de la API - Usa rutas limpias
const API_BASE_URL = '/api/pacientes';

/**
 * Realizar petición AJAX con rutas limpias
 */
async function fetchAPI(action, data = {}, method = 'GET') {
    try {
        // Mapear acciones a endpoints
        const endpoints = {
            'crear': '/crear',
            'buscar': '/buscar',
            'listar': '',
            'actualizar': '/actualizar',
            'eliminar': '/eliminar'
        };
        
        let url = API_BASE_URL + (endpoints[action] || '');
        let options = {
            method: method,
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            }
        };

        if (method === 'POST') {
            const formData = new URLSearchParams();
            for (const key in data) {
                formData.append(key, data[key]);
            }
            options.body = formData;
        } else if (method === 'GET' && Object.keys(data).length > 0) {
            const params = new URLSearchParams(data);
            url += `?${params.toString()}`;
        }

        const response = await fetch(url, options);
        const result = await response.json();
        
        return result;
        
    } catch (error) {
        console.error('Error en fetchAPI:', error);
        return {
            exito: false,
            mensaje: 'Error de conexión: ' + error.message,
            datos: null
        };
    }
}

/**
 * Navegar a una ruta
 */
function navigateTo(routeName, params = {}) {
    const routes = {
        'home': '/',
        'inicio': '/inicio',
        'pacientes.listar': '/pacientes',
        'pacientes.crear': '/pacientes/crear',
        'pacientes.editar': '/pacientes/editar'
    };
    
    let url = routes[routeName] || '/';
    
    // Agregar parámetros a la URL
    if (Object.keys(params).length > 0) {
        const queryString = new URLSearchParams(params).toString();
        url += '?' + queryString;
    }
    
    window.location.href = url;
}

/**
 * Mostrar spinner de carga
 */
function showLoading(elementId = 'loading') {
    const loading = document.getElementById(elementId);
    if (loading) {
        loading.style.display = 'flex';
    }
}

/**
 * Ocultar spinner de carga
 */
function hideLoading(elementId = 'loading') {
    const loading = document.getElementById(elementId);
    if (loading) {
        loading.style.display = 'none';
    }
}

/**
 * Formatear fecha de YYYY-MM-DD a DD/MM/YYYY
 */
function formatDate(dateString) {
    if (!dateString) return '';
    const [year, month, day] = dateString.split('-');
    return `${day}/${month}/${year}`;
}

/**
 * Formatear fecha de DD/MM/YYYY a YYYY-MM-DD
 */
function parseDate(dateString) {
    if (!dateString) return '';
    const [day, month, year] = dateString.split('/');
    return `${year}-${month}-${day}`;
}

/**
 * Calcular edad a partir de fecha de nacimiento
 */
function calculateAge(birthDate) {
    const birth = new Date(birthDate);
    const today = new Date();
    let age = today.getFullYear() - birth.getFullYear();
    const monthDiff = today.getMonth() - birth.getMonth();
    
    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
        age--;
    }
    
    return age;
}

/**
 * Formatear teléfono
 */
function formatPhone(phone) {
    if (!phone) return '';
    const cleaned = phone.replace(/\D/g, '');
    
    if (cleaned.length === 10) {
        return `(${cleaned.slice(0, 3)}) ${cleaned.slice(3, 6)}-${cleaned.slice(6)}`;
    }
    
    if (cleaned.length === 7) {
        return `${cleaned.slice(0, 3)}-${cleaned.slice(3)}`;
    }
    
    return phone;
}

/**
 * Validar cédula colombiana
 */
function validateCedula(cedula) {
    const cleaned = cedula.replace(/\D/g, '');
    return cleaned.length >= 6 && cleaned.length <= 10;
}

/**
 * Validar email
 */
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

/**
 * Validar teléfono colombiano
 */
function validatePhone(phone) {
    const cleaned = phone.replace(/\D/g, '');
    
    // Teléfono fijo (7 dígitos) o celular (10 dígitos empezando con 3)
    if (cleaned.length === 7) return true;
    if (cleaned.length === 10 && cleaned[0] === '3') return true;
    
    return false;
}

/**
 * Limpiar formulario
 */
function clearForm(formId) {
    const form = document.getElementById(formId);
    if (form) {
        form.reset();
    }
}

/**
 * Obtener icono de género
 */
function getGenderIcon(gender) {
    switch (gender) {
        case 'M':
            return '♂️';
        case 'F':
            return '♀️';
        default:
            return '⚧';
    }
}

/**
 * Obtener texto de género
 */
function getGenderText(gender) {
    switch (gender) {
        case 'M':
            return 'Masculino';
        case 'F':
            return 'Femenino';
        default:
            return 'Otro';
    }
}

/**
 * Scroll suave
 */
function smoothScroll(elementId) {
    const element = document.getElementById(elementId);
    if (element) {
        element.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}

/**
 * Copiar al portapapeles
 */
async function copyToClipboard(text) {
    try {
        await navigator.clipboard.writeText(text);
        showAlert('Copiado al portapapeles', 'success');
        return true;
    } catch (err) {
        console.error('Error al copiar:', err);
        return false;
    }
}

/**
 * Capitalizar primera letra
 */
function capitalize(str) {
    if (!str) return '';
    return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
}

/**
 * Capitalizar cada palabra
 */
function capitalizeWords(str) {
    if (!str) return '';
    return str.split(' ')
        .map(word => capitalize(word))
        .join(' ');
}

/**
 * Escapar HTML
 */
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

/**
 * Debounce function
 */
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

/**
 * Validar formulario
 */
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return false;
    
    let isValid = true;
    const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            input.classList.add('error');
            isValid = false;
        } else {
            input.classList.remove('error');
        }
    });
    
    return isValid;
}

/**
 * Inicialización al cargar la página
 */
document.addEventListener('DOMContentLoaded', function() {
    console.log('SaludTotal - Sistema iniciado con rutas protegidas');
    
    // Agregar clase de animación a elementos
    const cards = document.querySelectorAll('.card');
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.classList.add('fade-in');
        }, index * 100);
    });
    
    // Listener para inputs de fecha
    const dateInputs = document.querySelectorAll('input[type="date"]');
    dateInputs.forEach(input => {
        input.max = new Date().toISOString().split('T')[0];
    });
    
    // NOTA: El formateo de teléfonos se maneja individualmente en cada formulario
    // para evitar conflictos con la validación HTML5
});

// Exponer funciones globalmente
window.SaludTotal = {
    fetchAPI,
    navigateTo,
    showLoading,
    hideLoading,
    formatDate,
    parseDate,
    calculateAge,
    formatPhone,
    validateCedula,
    validateEmail,
    validatePhone,
    clearForm,
    getGenderIcon,
    getGenderText,
    smoothScroll,
    copyToClipboard,
    capitalize,
    capitalizeWords,
    escapeHtml,
    debounce,
    validateForm
};