<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Revisiones de Practicante</title>
    <link rel="stylesheet" href="{{ asset('css/lista_revisiones.css') }}">
        <link rel="stylesheet" href="{{ asset('css/menu_modal.css') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="header">
        <a href="#" class="back-button">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1>Lista de revisiones</h1>
        {{-- Aquí puedes incluir el botón de menú si lo necesitas en esta vista --}}
        <button class="menu-button" id="menuButton">
            <i class="fa-solid fa-bars"></i>
        </button>
    </div>
        @include('partials.menu_modal')


    <div class="main-content-wrapper">
        <div class="review-list-card">
            <div class="practicante-info-sidebar">
                <div class="practicante-avatar">
                    <i class="fa-solid fa-user"></i>
                </div>
                <p class="practicante-name">Leonardo Alatorre Esparza</p>
                <p class="practicante-area">Sistemas</p>
                <button class="add-review-button">Añadir revisión</button>
            </div>

            <div class="reviews-table-section">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Nº</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Evaluación general</th>
                                <th>Nombre Evaluador</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Revisión 1</td>
                                <td>Se comporta bien a la hora de desarrollar problemas de forma lógica</td>
                                <td class="rating-cell">
                                    <span>2/5</span>
                                    <div class="mini-stars">
                                        <i class="fa-solid fa-star selected"></i>
                                        <i class="fa-solid fa-star selected"></i>
                                        <i class="fa-solid fa-star unselected"></i>
                                        <i class="fa-solid fa-star unselected"></i>
                                        <i class="fa-solid fa-star unselected"></i>
                                    </div>
                                </td>
                                <td>Carlos Pacheco</td>
                            </tr>
                            <tr class="highlighted-row">
                                <td>2</td>
                                <td>Revisión 2</td>
                                <td>No cede ante las probocaciones de los clientes por querer un trato diferente</td>
                                <td class="rating-cell">
                                    <span>2/5</span>
                                    <div class="mini-stars">
                                        <i class="fa-solid fa-star selected"></i>
                                        <i class="fa-solid fa-star selected"></i>
                                        <i class="fa-solid fa-star unselected"></i>
                                        <i class="fa-solid fa-star unselected"></i>
                                        <i class="fa-solid fa-star unselected"></i>
                                    </div>
                                </td>
                                <td>Carlos Pacheco</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Mala conducta</td>
                                <td>Su manera de dirigirse a sus jefes es de mala educación</td>
                                <td class="rating-cell">
                                    <span>1/5</span>
                                    <div class="mini-stars">
                                        <i class="fa-solid fa-star selected"></i>
                                        <i class="fa-solid fa-star unselected"></i>
                                        <i class="fa-solid fa-star unselected"></i>
                                        <i class="fa-solid fa-star unselected"></i>
                                        <i class="fa-solid fa-star unselected"></i>
                                    </div>
                                </td>
                                <td>Carlos Pacheco</td>
                            </tr>
                            </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
        <script src="{{ asset('js/menu_modal.js') }}"></script>

</body>
</html>