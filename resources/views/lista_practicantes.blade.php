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
