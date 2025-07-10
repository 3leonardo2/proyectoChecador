<!DOCTYPE html>
<html lang="es">

<head>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Institución y/o Carrera</title>
    <link rel="stylesheet" href="{{ asset('css/registrar_insti.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/menu_modal.css') }}">

</head>

<body>
    <div class="header">
        @include('partials.regis_insti_modal')
        <h1>Registrar Institución y/o Carrera</h1>
        <button class="menu-button" id="menuButton">
            <i class="fa-solid fa-bars"></i>
        </button>
        @include('partials.menu_modal')

    </div>

    <div class="main-container">
        <form action="{{ route('instituciones.store') }}" method="POST" class="registrar_insti-wrapper"
            enctype="multipart/form-data">
            @csrf
            <div class="institucion-scrollable-content">
                <h2>Información de la institución</h2>
                <div class="form-group">
                    <label for="nombre_ins">Nombre*:</label>
                    <input type="text" id="nombre_ins" name="nombre_ins" placeholder="Nombre..." required>
                </div>
                <div class="form-group">
                    <label for="direccion_ins">Direción*:</label>
                    <input type="text" id="direccion_ins" name="direccion_ins" placeholder="Dirección..." required>
                </div>
                <div class="form-group">
                    <label for="telefono_ins">Teléfono de contacto*:</label>
                    <input type="text" id="telefono_ins" name="telefono_ins" placeholder="Teléfono...">
                </div>
                <div class="form-group">
                    <label for="correo_ins">Dirección de correo electrónico*:</label>
                    <input type="text" id="correo_ins" name="correo_ins" placeholder="Correo electrónico...">
                </div>

                <h2>Añadir carrera:</h2>
                <div id="carreras-container">
                    <div class="carrera-block" data-index="0">
                        <div class="form-group">
                            <label for="nombre_carr_0">Nombre:</label>
                            <input type="text" id="nombre_carr_0" name="carreras[0][nombre_carr]"
                                placeholder="Nombre de carrera...">
                        </div>
                        <div class="form-group">
                            <label for="gerente_carr_0">Gerente:</label>
                            <input type="text" id="gerente_carr_0" name="carreras[0][gerente_carr]"
                                placeholder="Gerente de carrera...">
                        </div>
                        <div class="form-group">
                            <label for="telefono_carr_0">Teléfono de contacto de carrera:</label>
                            <input type="text" id="telefono_carr_0" name="carreras[0][telefono_carr]"
                                placeholder="Teléfono de carrera...">
                        </div>
                        <div class="form-group">
                            <label for="correo_carr_0">Correo de contacto de carrera:</label>
                            <input type="text" id="correo_carr_0" name="carreras[0][correo_carr]"
                                placeholder="Correo de carrera...">
                        </div>
                    </div>
                </div>

                <button type="button" id="add-career-button" class="add-carrera"> Añadir otra carrera</button>

                <div class="form-actions">
                    <button type="submit" class="save-button">Guardar Institución</button>
                </div>

            </div>
        </form>
    </div>
    <script src="{{ asset('js/registrar_insti.js') }}"></script>
    <script src="{{ asset('js/menu_modal.js') }}"></script>

</body>

</html>
