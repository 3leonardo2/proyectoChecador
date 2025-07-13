document.addEventListener("DOMContentLoaded", function () {
    // Mostrar/ocultar popup de filtros
    const filterButton = document.getElementById("filterButton");
    const filterPopup = document.getElementById("filterPopup");

    filterButton.addEventListener("click", function () {
        filterPopup.style.display =
            filterPopup.style.display === "block" ? "none" : "block";
    });

    // Aplicar filtros
    document
        .querySelector(".apply-filter-button")
        .addEventListener("click", function () {
            const departamento = document.getElementById("filterDepartamento").value;
            const rol = document.getElementById("filterRol").value;

            const rows = document.querySelectorAll(".admin-row");

            rows.forEach((row) => {
                const rowDepartamento = row.getAttribute("data-departamento");
                const rowRol = row.getAttribute("data-rol");

                const departamentoMatch =
                    departamento === "" || rowDepartamento === departamento;
                const rolMatch = rol === "" || rowRol === rol;

                if (departamentoMatch && rolMatch) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });

            filterPopup.style.display = "none";
        });

    // Limpiar filtros
    document.querySelector('.clear-filter-button').addEventListener('click', function() {
        document.getElementById('filterDepartamento').value = '';
        document.getElementById('filterRol').value = '';
        
        const rows = document.querySelectorAll('.admin-row');
        rows.forEach(row => {
            row.style.display = '';
        });
        
        filterPopup.style.display = 'none';
    });

    // Búsqueda general por nombre o correo
    const searchInput = document.getElementById('searchInput');
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('.admin-row');
        
        rows.forEach(row => {
            const nombre = row.getAttribute('data-name').toLowerCase();
            const correo = row.getAttribute('data-correo').toLowerCase();
            
            if (nombre.includes(searchTerm) || correo.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
});