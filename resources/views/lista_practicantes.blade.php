<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Practicantes</title>
    <link rel="stylesheet" href="css/listadoprac.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/menu_modal.css') }}">
</head>

<body>
    <div class="header">
        <a href="#" class="back-button">
            <i class="fa-solid fa-arrow-left"></i>
        </a>

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
                            <option value="Sistemas">Sistemas</option>
                            <option value="Gastronomia">Gastronomía</option>
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
                        <th>Número</th>
                        <th>Administrar</th>
                        <th>Ver revisiones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="practicante-row" data-name="Leonardo" data-code="LAE" data-lastname="Alatorre Esparza"
                        data-area="Sistemas" data-school="UTZMG" data-estado="ACTIVO">
                        <td>A001</td>
                        <td>LAE</td>
                        <td>Leonardo</td>
                        <td>Alatorre Esparza</td>
                        <td>Sistemas</td>
                        <td>UTZMG</td>
                        <td>ACTIVO</td>
                        <td>3339018808</td>
                        <td><button class="admin-button"><i class="fa-solid fa-user-gear"></i></button></td>
                        <td><button class="review-button"><i class="fa-solid fa-star-half-stroke"></i></button></td>
                    </tr>
                    <tr class="practicante-row" data-name="Angel Hernán" data-code="AHAE"
                        data-lastname="Alatorre Esparza" data-area="Gastronomia" data-school="UTEJ"
                        data-estado="CONCLUIDO">
                        <td>A002</td>
                        <td>AHAE</td>
                        <td>Angel Hernán</td>
                        <td>Alatorre Esparza</td>
                        <td>Gastronomía</td>
                        <td>UTEJ</td>
                        <td>CONCLUIDO</td>
                        <td>3315487418</td>
                        <td><button class="admin-button"><i class="fa-solid fa-user-gear"></i></button></td>
                        <td><button class="review-button"><i class="fa-solid fa-star-half-stroke"></i></button></td>
                    </tr>
                    <tr class="practicante-row" data-name="Maria del Carmen" data-code="CAE"
                        data-lastname="Alatorre Esparza" data-area="Gastronomia" data-school="UTZMG"
                        data-estado="Conlcuido">
                        <td>A003</td>
                        <td>CAE</td>
                        <td>Maria del Carmen</td>
                        <td>Alatorre Esparza</td>
                        <td>Gastronomía</td>
                        <td>UTZMG</td>
                        <td>CONCLUIDO</td>
                        <td>3339018808</td>
                        <td><button class="admin-button"><i class="fa-solid fa-user-gear"></i></button></td>
                        <td><button class="review-button"><i class="fa-solid fa-star-half-stroke"></i></button></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><a  href="/detallesprac" class="admin-button"><i class="fa-solid fa-user-gear"></i></a></td>
                        <td><a href="/lista_revisiones" class="review-button"><i class="fa-solid fa-star-half-stroke"></i></a></td>

                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <script src="{{ asset('js/menu_modal.js') }}"></script>
    <script src="{{ asset('js/lista_prac.js') }}"></script>
</body>

</html>
