 // Lógica para la búsqueda y filtro (JavaScript)
        const searchInput = document.getElementById('searchInput');
        const filterButton = document.getElementById('filterButton');
        const filterPopup = document.getElementById('filterPopup');
        const applyFilterButton = document.querySelector('.apply-filter-button');
        const clearFilterButton = document.querySelector('.clear-filter-button');
        const practicanteRows = document.querySelectorAll('.practicante-row');

        // Función para aplicar filtros y búsqueda
        function applyFilters() {
            const searchText = searchInput.value.toLowerCase();
            const filterCodigo = document.getElementById('filterCodigo').value.toLowerCase();
            const filterArea = document.getElementById('filterArea').value.toLowerCase();
            const filterEscuela = document.getElementById('filterEscuela').value.toLowerCase();
            const filterEstado = document.getElementById('filterEstado').value.toLowerCase();

            practicanteRows.forEach(row => {
                const name = row.dataset.name.toLowerCase();
                const code = row.dataset.code.toLowerCase();
                const lastName = row.dataset.lastname
                    .toLowerCase(); // Asegúrate de que el data-lastname exista en tu HTML
                const area = row.dataset.area.toLowerCase();
                const school = row.dataset.school.toLowerCase();
                const estado = row.dataset.estado.toLowerCase();

                // Lógica de búsqueda mejorada: coincide con nombre, código O apellidos
                const matchesSearch = name.includes(searchText) ||
                    code.includes(searchText) ||
                    lastName.includes(searchText);

                const matchesCodigo = filterCodigo === '' || code.includes(filterCodigo);
                const matchesArea = filterArea === '' || area.includes(filterArea);
                const matchesEscuela = filterEscuela === '' || school.includes(filterEscuela);
                const matchesEstado = filterEstado === '' || estado.includes(filterEstado);

                if (matchesSearch && matchesCodigo && matchesArea && matchesEscuela && matchesEstado) {
                    row.style.display = ''; // Muestra la fila
                } else {
                    row.style.display = 'none'; // Oculta la fila
                }
            });
        }

        // Event Listeners
        searchInput.addEventListener('keyup', applyFilters); // Búsqueda en tiempo real
        filterButton.addEventListener('click', () => {
            filterPopup.classList.toggle('show'); // Muestra/oculta el pop-up
        });

        applyFilterButton.addEventListener('click', () => {
            applyFilters();
            filterPopup.classList.remove('show'); // Oculta el pop-up después de aplicar
        });

        clearFilterButton.addEventListener('click', () => {
            document.getElementById('filterCodigo').value = '';
            document.getElementById('filterArea').value = '';
            document.getElementById('filterEscuela').value = '';
            document.getElementById('filterEstado').value = '';
            applyFilters(); // Aplica los filtros después de limpiar
            filterPopup.classList.remove('show'); // Oculta el pop-up
        });

        // Ocultar el pop-up si se hace clic fuera de él
        window.addEventListener('click', (event) => {
            if (!filterPopup.contains(event.target) && !filterButton.contains(event.target)) {
                filterPopup.classList.remove('show');
            }
        });