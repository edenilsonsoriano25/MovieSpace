import './bootstrap';
import Swal from 'sweetalert2';
window.Swal = Swal;

// Auto-cerrar alertas después de 3 segundos
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        let alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            alert.style.display = 'none';
        });
    }, 3000);
});

// Confirmar acciones
window.confirmarAccion = function(mensaje) {
    return confirm(mensaje);
};

// Formatear moneda
window.formatearMoneda = function(valor) {
    return new Intl.NumberFormat('es-SV', {
        style: 'currency',
        currency: 'USD'
    }).format(valor);
};