<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Instituciones</title>
    <link rel="stylesheet" href="{{ asset('css/listadoprac.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lista_instituciones.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/menu_modal.css') }}">
</head>

<body>
    <div class="header">
        <h1>Instituciones y/o Escuelas</h1>
        <button class="menu-button" id="menuButton">
            <i class="fa-solid fa-bars"></i>
        </button>
    </div>
    @include('partials.menu_modal')

    <div class="main-container">
        <div class="search-filter-section">
            <div class="search-bar-container">
                <input type="text" id="searchInput" class="search-input" placeholder="Buscar por nombre o código...">
                <i class="fa-solid fa-magnifying-glass search-icon"></i>
            </div>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Dirección</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                        <th>Carreras</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($instituciones as $index => $institucion)
                        <tr class="institucion-row" data-id="{{ $institucion->id_institucion }}"
                            data-nombre="{{ $institucion->nombre }}" data-codigo="{{ $institucion->id_institucion }}">
                            <td>{{ $index + 1 }}</td>
                            <td>INS-{{ $institucion->id_institucion }}</td>
                            <td>{{ $institucion->nombre }}</td>
                            <td>{{ $institucion->direccion ?? 'N/A' }}</td>
                            <td>{{ $institucion->telefono ?? 'N/A' }}</td>
                            <td>{{ $institucion->correo ?? 'N/A' }}</td>
                            <td>
                                <span class="toggle-carreras"
                                    onclick="toggleCarreras({{ $institucion->id_institucion }})">
                                    {{ $institucion->carreras_count }} Carreras Registradas ▼
                                </span>
                                <div id="carreras-{{ $institucion->id_institucion }}" class="carreras-container">
                                    <ul class="carreras-list">
                                        <!-- Las carreras se cargarán aquí dinámicamente -->
                                    </ul>
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('instituciones.edit', $institucion->id_institucion) }}"
                                    class="admin-button">
                                    <i class="fa-solid fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script src="{{ asset('js/menu_modal.js') }}"></script>
    <script>
        function toggleCarreras(idInstitucion) {
            const container = document.getElementById(`carreras-${idInstitucion}`);
            const toggleSpan = container.previousElementSibling;

            // Cambiar el ícono del toggle
            if (container.style.display === 'block') {
                container.style.display = 'none';
                toggleSpan.innerHTML = `${toggleSpan.textContent.match(/\d+/)[0]} Carreras Registradas ▼`;
                return;
            }

            // Mostrar spinner mientras carga
            toggleSpan.innerHTML = `<i class="fas fa-spinner fa-spin"></i> Cargando...`;

            // Obtener carreras via AJAX
            fetch(`/instituciones/${idInstitucion}/carreras`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error en la respuesta del servidor');
                    }
                    return response.json();
                })
                .then(data => {
                    if (!data.success) {
                        throw new Error(data.message || 'Error al obtener carreras');
                    }

                    const list = container.querySelector('ul');
                    list.innerHTML = '';

                    if (data.carreras.length === 0) {
                        list.innerHTML = '<li class="no-carreras">No hay carreras registradas</li>';
                    } else {
                        data.carreras.forEach(carrera => {
                            const li = document.createElement('li');
                            li.className = 'carrera-item';
                            li.innerHTML = `
                        <strong>${carrera.nombre_carr}</strong>
                        <div class="carrera-details">
                            <small>Gerente: ${carrera.gerente_carr || 'N/A'}</small>
                            <small>Teléfono: ${carrera.tel_gerente || 'N/A'}</small>
                            <small>Correo: ${carrera.correo_carr || 'N/A'}</small>
                        </div>
                    `;
                            list.appendChild(li);
                        });
                    }

                    container.style.display = 'block';
                    toggleSpan.innerHTML = `${data.carreras.length} Carreras Registradas ▲`;
                })
                .catch(error => {
                    console.error('Error:', error);
                    const list = container.querySelector('ul');
                    list.innerHTML = `<li class="error-carrera">Error al cargar carreras: ${error.message}</li>`;
                    container.style.display = 'block';
                    toggleSpan.innerHTML = `Error ▼`;
                });
        }
        document.getElementById('searchInput').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('.institucion-row');

            rows.forEach(row => {
                const nombre = row.getAttribute('data-nombre').toLowerCase();
                const codigo = row.getAttribute('data-codigo').toLowerCase();

                if (nombre.includes(searchTerm) || codigo.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
</body>

</html>
