document.addEventListener('DOMContentLoaded', function() {
    // Selecciona el botón para alternar el sidebar
    const sidebarToggle = document.getElementById('sidebarToggle');
    
    // Selecciona el contenedor principal
    const wrapper = document.getElementById('wrapper');

    // Verifica que ambos elementos existan antes de añadir el evento
    if (sidebarToggle && wrapper) {
        sidebarToggle.addEventListener('click', function(event) {
            event.preventDefault(); // Previene cualquier comportamiento por defecto del botón
            
            // Alterna (añade o quita) la clase 'toggled' en el elemento wrapper
            wrapper.classList.toggle('toggled');
        });
    }
});