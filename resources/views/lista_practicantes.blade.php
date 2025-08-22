<!DOCTYPE html>
<html lang="es">

<head>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Practicantes</title>
    <link rel="stylesheet" href="{{ asset('css/lista_practicantes.css') }}">
    <link rel="stylesheet" href="{{ asset('css/paginacion.css') }}"> <!-- Añade esta línea -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/menu_modal.css') }}">
    <style>
        /* Estilos base comunes a ambos listados */
body {
    font-family: Arial, sans-serif;
    background-color: #f0f0f0;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

.header {
    background-color: #f0f0f0;
    padding: 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: relative;
    width: 100%;
    box-sizing: border-box;
    z-index: 10;
    border-bottom: 1px solid #e0e0e0;
}

.header .back-button {
    background-color: #d3bc68;
    color: white;
    border-radius: 50%;
    width: 45px;
    height: 45px;
    display: flex;
    justify-content: center;
    align-items: center;
    text-decoration: none;
    margin-right: 20px;
    position: absolute;
    left: 20px;
    top: 50%;
    transform: translateY(-50%);
    transition: 0.3s;
}

.back-button:hover {
    background-color: #c0a855;
    font-size: 20px;
    width: 50px;
    height: 50px;
}

.header h1 {
    margin: 0 auto;
    font-size: 28px;
    color: #333;
}

.header .menu-button {
    background-color: #d3bc68;
    color: white;
    border: none;
    border-radius: 50%;
    width: 45px;
    height: 45px;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
    font-size: 20px;
    position: absolute;
    right: 20px;
    top: 50%;
    transform: translateY(-50%);
    transition: 0.3s;
}

.menu-button:hover {
    background-color: #c0a855;
    font-size: 20px;
    width: 50px;
    height: 50px;
}

.main-container {
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 20px;
}

.search-filter-section {
    display: flex;
    gap: 15px;
    margin-bottom: 25px;
    width: 100%;
    max-width: 800px;
    justify-content: center;
    align-items: center;
    position: relative;
}

.search-bar-container {
    position: relative;
    flex-grow: 1;
}

.search-input {
    width: 100%;
    padding: 12px 40px 12px 20px;
    border: 1px solid #ccc;
    border-radius: 25px;
    font-size: 16px;
    outline: none;
    box-sizing: border-box;
    transition: 0.5s;
}

.search-input:hover {
    outline: none;
    box-sizing: border-box;
    height: 47px;
    font-size: 17px;
}

.search-icon {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #888;
}

.table-container {
    width: 100%;
    max-width: 1150px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    overflow-x: auto;
    margin-top: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
    min-width: 900px;
}

thead th {
    background-color: #583811;
    color: white;
    padding: 15px 10px;
    text-align: center;
    font-size: 15px;
    white-space: nowrap;
}

tbody td {
    padding: 12px 10px;
    border-bottom: 1px solid #eee;
    color: #333;
    font-size: 14px;
    white-space: nowrap;
    text-align: center;
}

.filter-button {
    background-color: #fff;
    border: 1px solid #ccc;
    border-radius: 50%;
    width: 45px;
    height: 45px;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
    font-size: 18px;
    color: #555;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    transition: 0.2s;
}

.filter-button:hover {
    font-size: 18px;
    color: #dfb75e;
    width: 50px;
    height: 50px;
}

.filter-popup {
    position: absolute;
    top: 100%;
    right: 0;
    background-color: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    z-index: 100;
    padding: 20px;
    display: none;
    flex-direction: column;
    gap: 15px;
    width: 280px;
    box-sizing: border-box;
}

.filter-popup.show {
    display: flex;
}

.filter-popup h3 {
    margin-top: 0;
    margin-bottom: 15px;
    color: #333;
    border-bottom: 1px solid #eee;
    padding-bottom: 10px;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.filter-group label {
    font-weight: bold;
    color: #666;
    font-size: 14px;
}

.filter-group input[type="text"],
.filter-group select {
    padding: 8px 12px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 14px;
    width: 100%;
    box-sizing: border-box;
}

.filter-actions {
    display: flex;
    justify-content: space-between;
    margin-top: 20px;
}

.apply-filter-button,
.clear-filter-button {
    padding: 10px 15px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
    border: none;
    transition: background-color 0.2s;
}

.apply-filter-button {
    background-color: #d3bc68;
    color: white;
    transition: 0.5s;
}

.apply-filter-button:hover {
    background-color: #c0a855 !important;
    color: white !important;
    border-color: #474747 !important;
}

.clear-filter-button {
    background-color: #f0f0f0;
    color: #555;
    border: 1px solid #f0f0f0;
    transition: 0.5s;
}

.clear-filter-button:hover {
    background-color: #c2c2c2;
    border-color: #c2c2c2;
}

/* Estilos para badges de estado */
.status-badge {
    padding: 5px 10px;
    border-radius: 15px;
    font-size: 12px;
    font-weight: bold;
    text-transform: uppercase;
}

.status-badge.activo {
    background-color: #d4edda;
    color: #155724;
}

.status-badge.concluido {
    background-color: #f8d7da;
    color: #721c24;
}

/* Estilos para filas de practicantes */
tbody tr:nth-child(even) {
    background-color: #f6e8d0;
}

tbody tr:nth-child(odd) {
    background-color: #ceb15d;
}

.filter-popup {
    position: absolute;
    right: 0;
    background: white;
    padding: 15px;
    border: 1px solid #ddd;
    border-radius: 5px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    z-index: 1000;
    display: none;
}

.filter-popup.show {
    display: block;
}

#monthFilterGroup {
    margin-top: 10px;
    padding-top: 10px;
    border-top: 1px solid #eee;
}


            .acciones-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background-color: #7f4e20;
        color: white;
        text-decoration: none;
        margin: 0 3px;
        transition: all 0.3s ease;
    }

    .acciones-button:hover {
        background-color: white;
        transform: scale(1.1);
        color: #7f4e20;
    }

    .borrar-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background-color: #7f4e20;
        color: white;
        text-decoration: none;
        margin: 0 3px;
        transition: all 0.3s ease;
        background-color: #e74c3c;
    }

    .borrar-button:hover {
        background-color: #c0392b;
        transform: scale(1.1);
    }
    </style>
</head>

<body>
    @include('partials.detalles_modal')
    <div class="header">
        <h1>Practicantes</h1>
        <button class="menu-button" id="menuButton">
            <i class="fa-solid fa-bars"></i>
        </button>
    </div>
    @include('partials.menu_modal')

    <div class="main-container">
        <div class="search-filter-section">
            <div class="search-bar-container">
                <input type="text" id="searchInput" class="search-input"
                    placeholder="Buscar por clave, nombre o apellidos...">
                <i class="fa-solid fa-magnifying-glass search-icon"></i>
            </div>
            <button class="filter-button" id="filterButton">
                <i class="fa-solid fa-filter"></i>
            </button>

            <div class="filter-popup" id="filterPopup">
                <div class="filter-content">
                    <h3>Filtrar por:</h3>
                    <div class="filter-group">
                        <label for="filterCodigo">Código:</label>
                        <input type="text" id="filterCodigo">
                    </div>
                    <div class="filter-group">
                        <label for="filterArea">Área:</label>
                        <select id="filterArea">
                            <option value="">Todas</option>
                            <option value="Contraloria">Contraloria</option>
                            <option value="Ventas">Ventas</option>
                            <option value="Sistemas">Sistemas</option>
                            <option value="AyB">AyB</option>
                            <option value="Mantenimiento">Mantenimiento</option>
                            <option value="Recursos Humanos">Recursos Humanos</option>
                            <option value="Dirección">Dirección</option>
                            <option value="Recepción">Recepción</option>
                            <option value="Reservaciones">Reservaciones</option>
                            <option value="Cocina">Cocina</option>
                            <option value="Ama de llaves">Ama de llaves</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="filterEscuela">Escuela o institución:</label>
                        <input type="text" id="filterEscuela">
                    </div>
                    <div class="filter-group">
                        <label for="filterEstado">Estado:</label>
                        <select id="filterEstado">
                            <option value="">Todos</option>
                            <option value="ACTIVO">ACTIVO</option>
                            <option value="CONCLUIDO">CONCLUIDO</option>
                        </select>
                    </div>
                    <div class="filter-group" id="monthFilterGroup">
                        <label for="filterMes">Seleccionar mes:</label>
                        <select id="filterMes">
                            <option value="">Todos</option>
                            <option value="1">Enero</option>
                            <option value="2">Febrero</option>
                            <option value="3">Marzo</option>
                            <option value="4">Abril</option>
                            <option value="5">Mayo</option>
                            <option value="6">Junio</option>
                            <option value="7">Julio</option>
                            <option value="8">Agosto</option>
                            <option value="9">Septiembre</option>
                            <option value="10">Octubre</option>
                            <option value="11">Noviembre</option>
                            <option value="12">Diciembre</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for ="filterProximos30">
                            <input type="checkbox" id="filterProximos30"> Próximos 30 días
                        </label>
                    </div>


                    <div class="filter-actions">
                        <button class="apply-filter-button">Aplicar Filtros</button>
                        <button class="clear-filter-button">Limpiar Filtros</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Número</th>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Área</th>
                        <th>Escuela o institución</th>
                        <th>ESTADO</th>
                        <th>Fecha Finalización</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($practicantes as $index => $practicante)
                        <tr class="practicante-row" data-name="{{ $practicante->nombre }}"
                            data-code="{{ $practicante->codigo }}" data-lastname="{{ $practicante->apellidos }}"
                            data-area="{{ $practicante->area_asignada }}"
                            data-school="{{ $practicante->institucion->nombre }}"
                            data-estado="{{ $practicante->estado_practicas }}"
                            data-fecha-fin="{{ $practicante->fecha_final }}">
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $practicante->codigo }}</td>
                            <td>{{ $practicante->nombre }}</td>
                            <td>{{ $practicante->apellidos }}</td>
                            <td>{{ $practicante->area_asignada }}</td>
                            <td>{{ $practicante->institucion->acronimo }}</td>
                            <td>
                                <span class="status-badge {{ strtolower($practicante->estado_practicas) }}">
                                    {{ $practicante->estado_practicas }}
                                </span>
                            </td>
                            <td>{{ $practicante->fecha_final }}</td>
                            <td>
                                @if (isset($practicante->id_practicante))
                                    <a href="{{ route('practicantes.show', parameters: $practicante->id_practicante) }}"
                                        class="acciones-button">
                                        <i class="fa-solid fa-user-gear"></i>
                                    </a>
                                    @php
                                        $nombreCompleto = $practicante->nombre . ' ' . $practicante->apellidos;
                                    @endphp
                                    <form action="{{ route('practicantes.destroy', $practicante->id_practicante) }}"
                                        method="POST"
                                        onsubmit="return confirm('¿Estás seguro de que quieres eliminar a {{ addslashes($nombreCompleto) }}? Esta acción no se puede deshacer.');">
                                        @csrf
                                        @method('DELETE') {{-- Esto simula una petición DELETE --}}
                                        <button type="submit" class=" borrar-button" title="Eliminar">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
        @if ($practicantes->hasPages())
            <div class="pagination-container">
                <ul class="pagination">
                    {{-- Enlace anterior --}}
                    @if ($practicantes->onFirstPage())
                        <li class="disabled"><span>&laquo; Anterior</span></li>
                    @else
                        <li><a href="{{ $practicantes->previousPageUrl() }}" rel="prev">&laquo; Anterior</a></li>
                    @endif

                    {{-- Elementos de paginación --}}
                    @foreach ($practicantes->getUrlRange(1, $practicantes->lastPage()) as $page => $url)
                        @if ($page == $practicantes->currentPage())
                            <li class="active"><span>{{ $page }}</span></li>
                        @else
                            <li><a href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach

                    {{-- Enlace siguiente --}}
                    @if ($practicantes->hasMorePages())
                        <li><a href="{{ $practicantes->nextPageUrl() }}" rel="next">Siguiente &raquo;</a></li>
                    @else
                        <li class="disabled"><span>Siguiente &raquo;</span></li>
                    @endif
                </ul>
            </div>
        @endif
    </div>

    <script src="{{ asset('js/menu_modal.js') }}"></script>
    <script src="{{ asset('js/lista_prac.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                showAlertModal('success', '{{ session('success') }}');
            @endif

            @if (session('error'))
                showAlertModal('error', '{{ session('error') }}');
            @endif
            const mostrarActivos = @json($mostrarActivos ?? false);

            if (mostrarActivos) {
                document.getElementById('filterEstado').value = 'ACTIVO';
                document.querySelector('.apply-filter-button').click();
            }
        });

        function showAlertModal(type, message) {
            const modal = document.getElementById('alertModal');
            const icon = document.getElementById('alertModalIcon');
            const msg = document.getElementById('alertModalMessage');

            // Configura el modal según el tipo
            modal.className = `alert-modal ${type}`;
            icon.innerHTML = type === 'success' ?
                '<i class="fas fa-check-circle"></i>' :
                '<i class="fas fa-exclamation-circle"></i>';
            msg.textContent = message;

            // Muestra el modal
            modal.style.display = 'flex';

            // Cierra automáticamente después de 5 segundos
            setTimeout(() => {
                modal.style.animation = 'fadeOut 0.5s ease-out';
                setTimeout(() => {
                    modal.style.display = 'none';
                }, 500);
            }, 5000);
        }
    </script>
</body>

</html>
