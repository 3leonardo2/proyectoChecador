<!DOCTYPE html>
<html lang="es">

<head>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Practicantes</title>
    <link rel="stylesheet" href="{{ asset('css/listadoprac.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lista_practicantes.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/menu_modal.css') }}">
    <style>
        #monthFilterGroup {
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid #eee;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Practicantes</h1>
        <button class="menu-button" id="menuButton">
            <i class="fa-solid fa-bars"></i>
        </button>
    </div>
    @include('partials.menu_modal')

    <div class="main-container">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

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
                                        class="admin-button">
                                        <i class="fa-solid fa-user-gear"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <script src="{{ asset('js/menu_modal.js') }}"></script>
    <script src="{{ asset('js/lista_prac.js') }}"></script>
</body>

</html>
