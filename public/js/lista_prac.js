document.addEventListener('DOMContentLoaded', function() {
    // Mostrar/ocultar popup de filtros
    const filterButton = document.getElementById('filterButton');
    const filterPopup = document.getElementById('filterPopup');
    
    filterButton.addEventListener('click', function() {
        filterPopup.style.display = filterPopup.style.display === 'block' ? 'none' : 'block';
    });

    // Aplicar filtros
    document.querySelector('.apply-filter-button').addEventListener('click', function() {
        const codigo = document.getElementById('filterCodigo').value.toLowerCase();
        const area = document.getElementById('filterArea').value;
        const escuela = document.getElementById('filterEscuela').value.toLowerCase();
        const estado = document.getElementById('filterEstado').value;
        
        const rows = document.querySelectorAll('.practicante-row');
        
        rows.forEach(row => {
            const rowCodigo = row.getAttribute('data-code').toLowerCase();
            const rowArea = row.getAttribute('data-area');
            const rowEscuela = row.getAttribute('data-school').toLowerCase();
            const rowEstado = row.getAttribute('data-estado');
            
            const codigoMatch = codigo === '' || rowCodigo.includes(codigo);
            const areaMatch = area === '' || rowArea === area;
            const escuelaMatch = escuela === '' || rowEscuela.includes(escuela);
            const estadoMatch = estado === '' || rowEstado === estado;
            
            if (codigoMatch && areaMatch && escuelaMatch && estadoMatch) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
        
        filterPopup.style.display = 'none';
    });

    // Limpiar filtros
    document.querySelector('.clear-filter-button').addEventListener('click', function() {
        document.getElementById('filterCodigo').value = '';
        document.getElementById('filterArea').value = '';
        document.getElementById('filterEscuela').value = '';
        document.getElementById('filterEstado').value = '';
        
        const rows = document.querySelectorAll('.practicante-row');
        rows.forEach(row => {
            row.style.display = '';
        });
        
        filterPopup.style.display = 'none';
    });

    // Búsqueda general
    const searchInput = document.getElementById('searchInput');
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('.practicante-row');
        
        rows.forEach(row => {
            const nombre = row.getAttribute('data-name').toLowerCase();
            const apellidos = row.getAttribute('data-lastname').toLowerCase();
            const codigo = row.getAttribute('data-code').toLowerCase();
            
            if (nombre.includes(searchTerm) || 
                apellidos.includes(searchTerm) || 
                codigo.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
});