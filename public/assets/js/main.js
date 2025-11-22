/**
 * SaludTotal - JavaScript Principal
 * Funciones globales y utilidades (rutas protegidas)
 */

// Base de rutas inyectada por PHP (ver header.php)
const BASE_PATH = (typeof window !== 'undefined' && window.APP_BASE_PATH)
    ? String(window.APP_BASE_PATH).replace(/\/+$/, '')
    : '';

// URL base del gateway SOAP protegido
const SOAP_BASE_URL = (typeof window !== 'undefined' && window.SOAP_BASE_URL)
    ? String(window.SOAP_BASE_URL).replace(/\/+$/, '')
    : `${BASE_PATH}/soap/pacientes`.replace(/\/+$/, '');

// Construir ruta respetando base_path
const buildPath = (path = '') => {
    const normalized = path.startsWith('/') ? path : `/${path}`;
    const prefix = BASE_PATH ? `${BASE_PATH}` : '';
    return `${prefix}${normalized}` || '/';
};

/**
 * Realizar peticion SOAP (gateway) con rutas limpias
 */
async function fetchSOAP(action, data = {}, method = 'GET') {
    try {
        const endpoints = {
            crear: '/crear',
            buscar: '/buscar',
            listar: '',
            actualizar: '/actualizar',
            eliminar: '/eliminar'
        };

        const base = SOAP_BASE_URL.endsWith('/') ? SOAP_BASE_URL.slice(0, -1) : SOAP_BASE_URL;
        let url = `${base}${endpoints[action] || ''}`;

        const options = {
            method,
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        };

        if (method === 'POST') {
            const formData = new URLSearchParams();
            Object.keys(data).forEach(key => formData.append(key, data[key]));
            options.body = formData;
        } else if (method === 'GET' && Object.keys(data).length > 0) {
            const params = new URLSearchParams(data);
            url += `?${params.toString()}`;
        }

        const response = await fetch(url, options);
        const result = await response.json();
        return result;
    } catch (error) {
        console.error('Error en fetchSOAP:', error);
        return {
            exito: false,
            mensaje: 'Error de conexion: ' + error.message,
            datos: null
        };
    }
}

/**
 * Navegar a una ruta
 */
function navigateTo(routeName, params = {}) {
    const routes = {
        home: '/',
        inicio: '/inicio',
        'pacientes.listar': '/pacientes',
        'pacientes.crear': '/pacientes/crear',
        'pacientes.editar': '/pacientes/editar'
    };

    const path = routes[routeName] || '/';
    let url = buildPath(path);

    if (Object.keys(params).length > 0) {
        const queryString = new URLSearchParams(params).toString();
        url += `?${queryString}`;
    }

    window.location.href = url;
}

function showLoading(elementId = 'loading') {
    const loading = document.getElementById(elementId);
    if (loading) loading.style.display = 'flex';
}

function hideLoading(elementId = 'loading') {
    const loading = document.getElementById(elementId);
    if (loading) loading.style.display = 'none';
}

function formatDate(dateString) {
    if (!dateString) return '';
    const [year, month, day] = dateString.split('-');
    return `${day}/${month}/${year}`;
}

function parseDate(dateString) {
    if (!dateString) return '';
    const [day, month, year] = dateString.split('/');
    return `${year}-${month}-${day}`;
}

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

function formatPhone(phone) {
    if (!phone) return '';
    const cleaned = phone.replace(/\D/g, '');
    if (cleaned.length === 10) return `(${cleaned.slice(0, 3)}) ${cleaned.slice(3, 6)}-${cleaned.slice(6)}`;
    if (cleaned.length === 7) return `${cleaned.slice(0, 3)}-${cleaned.slice(3)}`;
    return phone;
}

function validateCedula(cedula) {
    const cleaned = cedula.replace(/\D/g, '');
    return cleaned.length >= 6 && cleaned.length <= 10;
}

function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

function validatePhone(phone) {
    const cleaned = phone.replace(/\D/g, '');
    if (cleaned.length === 7) return true;
    if (cleaned.length === 10 && cleaned[0] === '3') return true;
    return false;
}

function clearForm(formId) {
    const form = document.getElementById(formId);
    if (form) form.reset();
}

function getGenderIcon(gender) {
    switch (gender) {
        case 'M':
            return '?';
        case 'F':
            return '?';
        default:
            return '?';
    }
}

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

function smoothScroll(elementId) {
    const element = document.getElementById(elementId);
    if (element) element.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

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

function capitalize(str) {
    if (!str) return '';
    return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
}

function capitalizeWords(str) {
    if (!str) return '';
    return str
        .split(' ')
        .map(word => capitalize(word))
        .join(' ');
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

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

// Inicializacion al cargar la pagina

document.addEventListener('DOMContentLoaded', function() {
    console.log('SaludTotal - Sistema iniciado con rutas protegidas');

    const cards = document.querySelectorAll('.card');
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.classList.add('fade-in');
        }, index * 100);
    });

    const dateInputs = document.querySelectorAll('input[type="date"]');
    dateInputs.forEach(input => {
        input.max = new Date().toISOString().split('T')[0];
    });
});

// Exponer funciones globalmente
const fetchAPI = fetchSOAP; // alias para compatibilidad

window.SaludTotal = {
    fetchSOAP,
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
