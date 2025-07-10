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
                            <option value="Sistemas">Sistemas</option>
                            <option value="Mantenimiento">Mantenimiento</option>
                            <option value="Gastronomía">Gastronomía</option>
                            <option value="Panadería">Panadería</option>
                            <option value="Concierge">Concierge</option>
                            <option value="Ventas">Ventas</option>
                            <option value="LLamadas">Llamadas</option>
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
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($practicantes as $index => $practicante)
                        <tr class="practicante-row" data-name="{{ $practicante->nombre }}"
                            data-code="{{ $practicante->codigo }}" data-lastname="{{ $practicante->apellidos }}"
                            data-area="{{ $practicante->area_asignada }}"
                            data-school="{{ $practicante->institucion->nombre }}"
                            data-estado="{{ $practicante->estado_practicas }}">
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $practicante->codigo }}</td>
                            <td>{{ $practicante->nombre }}</td>
                            <td>{{ $practicante->apellidos }}</td>
                            <td>{{ $practicante->area_asignada }}</td>
                            <td>{{ $practicante->institucion->nombre }}</td>
                            <td>
                                <span class="status-badge {{ strtolower($practicante->estado_practicas) }}">
                                    {{ $practicante->estado_practicas }}
                                </span>
                            </td>
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
