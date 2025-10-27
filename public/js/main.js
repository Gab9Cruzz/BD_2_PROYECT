/**
 * Script principal del sistema de inventario
 * Funciones comunes para todas las páginas
 */

// Confirmación de eliminación
function confirmarEliminacion(mensaje) {
    return confirm(mensaje || '¿Está seguro de que desea eliminar este elemento?');
}

// Formatear moneda
function formatearMoneda(valor) {
    return 'S/ ' + parseFloat(valor).toFixed(2);
}

// Validar número positivo
function validarNumeroPositivo(input) {
    if (parseFloat(input.value) < 0) {
        input.value = 0;
        alert('El valor no puede ser negativo');
    }
}

// Auto-cerrar alertas después de 5 segundos
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
});

// Prevenir envío de formulario con Enter (excepto en textarea)
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form');
    forms.forEach(function(form) {
        form.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA') {
                e.preventDefault();
            }
        });
    });
});

// Validar stock antes de agregar a venta
function validarStockDisponible(select, cantidad) {
    const option = select.options[select.selectedIndex];
    const stockDisponible = parseInt(option.getAttribute('data-stock')) || 0;
    const cantidadSolicitada = parseInt(cantidad.value) || 0;
    
    if (cantidadSolicitada > stockDisponible) {
        alert('Stock insuficiente. Disponible: ' + stockDisponible);
        cantidad.value = stockDisponible;
        return false;
    }
    return true;
}

// Confirmar antes de salir si hay cambios sin guardar
let formularioModificado = false;

document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('input, select, textarea');
    inputs.forEach(function(input) {
        input.addEventListener('change', function() {
            formularioModificado = true;
        });
    });
    
    const forms = document.querySelectorAll('form');
    forms.forEach(function(form) {
        form.addEventListener('submit', function() {
            formularioModificado = false;
        });
    });
});

window.addEventListener('beforeunload', function(e) {
    if (formularioModificado) {
        e.preventDefault();
        e.returnValue = '¿Está seguro de que desea salir? Los cambios no guardados se perderán.';
    }
});

// Filtrar tabla en tiempo real
function filtrarTabla(inputId, tablaId) {
    const input = document.getElementById(inputId);
    const tabla = document.getElementById(tablaId);
    const filas = tabla.getElementsByTagName('tr');
    
    input.addEventListener('keyup', function() {
        const filtro = this.value.toLowerCase();
        
        for (let i = 1; i < filas.length; i++) {
            const fila = filas[i];
            const texto = fila.textContent.toLowerCase();
            
            if (texto.includes(filtro)) {
                fila.style.display = '';
            } else {
                fila.style.display = 'none';
            }
        }
    });
}

// Imprimir sección específica
function imprimirSeccion(elementId) {
    const contenido = document.getElementById(elementId);
    const ventana = window.open('', '', 'width=800,height=600');
    
    ventana.document.write('<html><head><title>Imprimir</title>');
    ventana.document.write('<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">');
    ventana.document.write('</head><body>');
    ventana.document.write(contenido.innerHTML);
    ventana.document.write('</body></html>');
    
    ventana.document.close();
    ventana.print();
}

// Tooltip de Bootstrap
document.addEventListener('DOMContentLoaded', function() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

console.log('✅ Sistema de Inventario cargado correctamente');
