{{-- filepath: resources/views/lista_administradores.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Administradores</title>
    <link rel="stylesheet" href="{{ asset('css/listadoprac.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lista_practicantes.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/menu_modal.css') }}">
</head>
<body>
    <div class="header">
        <h1>Administradores</h1>
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
                    placeholder="Buscar por nombre o correo...">
                <i class="fa-solid fa-magnifying-glass search-icon"></i>
            </div>
            <button class="filter-button" id="filterButton">
                <i class="fa-solid fa-filter"></i>
            </button>

            <div class="filter-popup" id="filterPopup">
                <div class="filter-content">
                    <h3>Filtrar por:</h3>
                    <div class="filter-group">
                        <label for="filterDepartamento">Departamento:</label>
                        <select id="filterDepartamento">
                            <option value="">Todos</option>
                            <option value="Recursos Humanos">Recursos Humanos</option>
                            <option value="Sistemas">Sistemas</option>
                            <option value="Administración">Administración</option>
                            <option value="Direccion">Dirección</option>
                            <option value="Cocina">Cocina</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="filterRol">Rol:</label>
                        <select id="filterRol">
                            <option value="">Todos</option>
                            <option value="rh">Administrador de aplicación</option>
                            <option value="asesor">Asesor de departamento</option>
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
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Departamento</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($administradores as $index => $admin)
                        <tr class="admin-row"
                            data-name="{{ $admin->nombre }}"
                            data-correo="{{ $admin->correo }}"
                            data-departamento="{{ $admin->departamento }}"
                            data-rol="{{ $admin->rol }}">
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $admin->nombre }}</td>
                            <td>{{ $admin->correo }}</td>
                            <td>{{ $admin->departamento }}</td>
                            <td>
                                <span class="status-badge {{ $admin->rol === 'rh' ? 'rh' : 'asesor' }}">
                                    {{ $admin->rol === 'rh' ? 'Administrador de aplicación' : 'Asesor de departamento' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('administradores.edit', $admin->id_admin) }}" class="admin-button" title="Editar">
                                    <i class="fa-solid fa-user-pen"></i>
                                </a>
                                {{-- Puedes agregar más acciones aquí, como eliminar --}}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <script src="{{ asset('js/menu_modal.js') }}"></script>
    {{-- Puedes reutilizar tu js/lista_prac.js para filtros y búsqueda, solo cambia los selectores si es necesario --}}
</body>
</html>