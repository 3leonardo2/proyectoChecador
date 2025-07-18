document.addEventListener("DOMContentLoaded", function () {
    // Elementos del DOM
    const filterButton = document.getElementById("filterButton");
    const filterPopup = document.getElementById("filterPopup");
    const filterProximos30 = document.getElementById("filterProximos30");
    const filterMes = document.getElementById("filterMes");
    const searchInput = document.getElementById("searchInput");

    // Mostrar/ocultar popup de filtros
    filterButton.addEventListener("click", function () {
        filterPopup.style.display =
            filterPopup.style.display === "block" ? "none" : "block";
    });

    // Funciones para comparar fechas
    function isValidDate(dateString) {
        return dateString && dateString !== 'null' && !isNaN(new Date(dateString).getTime());
    }

    function isWithin30Days(endDate) {
        if (!isValidDate(endDate)) return false;
        const today = new Date();
        today.setHours(0, 0, 0, 0); // Normalize today to the start of the day

        const end = new Date(endDate);
        end.setHours(0, 0, 0, 0); // Normalize end date to the start of its day

        const diffTime = end.getTime() - today.getTime();
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); // Use Math.ceil to include the end day

        return diffDays >= 0 && diffDays <= 30;
    }

    function isInSelectedMonth(endDate, selectedMonth) {
        if (!isValidDate(endDate)) return false;
        const end = new Date(endDate);
        const today = new Date(); // To get the current year

        return (end.getMonth() + 1) === parseInt(selectedMonth) &&
               end.getFullYear() === today.getFullYear(); // Filter for current year
    }

    // Aplicar filtros
    document.querySelector('.apply-filter-button').addEventListener('click', function() {
        const codigo = document.getElementById('filterCodigo').value.trim().toLowerCase();
        const area = document.getElementById('filterArea').value;
        const escuela = document.getElementById('filterEscuela').value.trim().toLowerCase();
        const estado = document.getElementById('filterEstado').value;
        const proximos30 = filterProximos30.checked;
        const mes = filterMes.value;

        document.querySelectorAll('.practicante-row').forEach(row => {
            // Obtener valores de los atributos de datos
            const rowCodigo = row.getAttribute('data-code')?.toLowerCase() || '';
            const rowArea = row.getAttribute('data-area') || '';
            const rowEscuela = row.getAttribute('data-school')?.toLowerCase() || '';
            const rowEstado = row.getAttribute('data-estado') || '';
            const fechaFin = row.getAttribute('data-fecha-fin');

            // Aplicar condiciones de filtrado
            const codigoMatch = !codigo || rowCodigo.includes(codigo);
            const areaMatch = !area || rowArea === area;
            const escuelaMatch = !escuela || rowEscuela.includes(escuela);
            const estadoMatch = !estado || rowEstado === estado;

            // Aplicar filtros de fecha - mutually exclusive logic
            let fechaMatch = true;
            if (proximos30) {
                fechaMatch = isWithin30Days(fechaFin);
            } else if (mes) { // Only apply month filter if 'proximos30' is NOT checked
                fechaMatch = isInSelectedMonth(fechaFin, mes);
            }

            // Mostrar u ocultar fila según los filtros
            row.style.display = (codigoMatch && areaMatch && escuelaMatch && estadoMatch && fechaMatch)
                ? ''
                : 'none';
        });

        filterPopup.style.display = 'none';
    });

    // Limpiar filtros
     // Limpiar filtros
    document.querySelector('.clear-filter-button').addEventListener('click', function() {
        document.getElementById('filterCodigo').value = '';
        document.getElementById('filterArea').value = '';
        document.getElementById('filterEscuela').value = '';
        document.getElementById('filterEstado').value = '';
        filterProximos30.checked = false;
        filterMes.value = '';

        document.querySelectorAll('.practicante-row').forEach(row => {
            row.style.display = '';
        });
    });

    // Búsqueda general
    searchInput.addEventListener('input', function() {
        const term = this.value.trim().toLowerCase();
        if (!term) {
            document.querySelectorAll('.practicante-row').forEach(row => {
                row.style.display = '';
            });
            return;
        }

        document.querySelectorAll('.practicante-row').forEach(row => {
            const nombre = row.getAttribute('data-name')?.toLowerCase() || '';
            const apellidos = row.getAttribute('data-lastname')?.toLowerCase() || '';
            const codigo = row.getAttribute('data-code')?.toLowerCase() || '';

            const show = nombre.includes(term) ||
                         apellidos.includes(term) ||
                         codigo.includes(term);

            row.style.display = show ? '' : 'none';
        });
    });
});
