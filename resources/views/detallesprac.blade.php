<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de practicante</title>
    <link rel="stylesheet" href="css/detailsprac.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/menu_modal.css') }}">
</head>

<body>
    <div class="header">
        <a href="#" class="back-button">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1>Detalles de practicante</h1>

        <button class="menu-button" id="menuButton">
            <i class="fa-solid fa-bars"></i>
        </button>
    </div>
    @include('partials.menu_modal')
    <div class="main-container">
        <div class="practicante-info-wrapper">
            <div class="practicante-fixed-elements">
                <div class="practicante-profile-section">
                    <div class="profile-image-container">
                        <img src="{{ asset('img_prac/leonardo.jfif') }}" alt="ImagenPracticante" class="profile-image">
                    </div>
                    <div class="practicante-codigo">Código: LAE</div>
                </div>
            </div>
            <a href="/edit_prac" class="edit-button">
                <i class="fa-solid fa-pen-to-square"></i>
                Editar
            </a>
            <button class="revision-button">
                <i class="fa-solid fa-ranking-star"></i> Ver revisiones
            </button>
            <div class="practicante-scrollable-content">
                <!-- ------------------------------------------------------ -->
                <h2>Datos Generales</h2>
                <div class="data-item">
                    <label>Nombre:</label>
                    <p>Leonardo</p>
                </div>
                <div class="data-item">
                    <label>Apellidos:</label>
                    <p>Alatorre Esparza</p>
                </div>
                <div class="data-item">
                    <label>Fecha de nacimiento:</label>
                    <p>13/06/2004</p>
                </div>
                <div class="data-item">
                    <label>Sexo:</label>
                    <p>Hombre</p>
                </div>
                <div class="data-item">
                    <label>Correo electrónico personal:</label>
                    <p>leoestudiosxd@gmail.com</p>
                </div>
                <div class="data-item">
                    <label>Teléfono personal:</label>
                    <p>3339018808</p>
                </div>
                <div class="data-item">
                    <label>Nombre de emergéncia:</label>
                    <p>Verenice Esparza Ruíz</p>
                </div>
                <div class="data-item">
                    <label>Teléfono de emergéncia:</label>
                    <p>8884848488</p>
                </div>
                <div class="data-item">
                    <label>Número de seguro:</label>
                    <p>03190439202</p>
                </div>
                <!-- ------------------------------------------------------ -->
                <h2>Información institucional:</h2>
                <div class="data-item">
                    <label>Escuela o institución:</label>
                    <p>UTZMG</p>
                </div>
                <div class="data-item">
                    <label>Carrera:</label>
                    <p>Ingeniería en Desarrollo de Software</p>
                </div>
                <div class="data-item">
                    <label>Correo:</label>
                    <p>info@utzmg.edu.mx</p>
                </div>
                <div class="data-item">
                    <label>Teléfono:</label>
                    <p>33 1234 5678</p>
                </div>
                <div class="data-item">
                    <label>Dirección:</label>
                    <p>Calle Falsa 123, Colonia Inventada, Zapopan, Jalisco</p>
                </div>
                <div class="data-item">
                    <label>Nivel de estudios:</label>
                    <p>Licenciatura</p>
                </div>
                <!-- ------------------------------------------------------ -->
                <h2>Información de prácticas:</h2>
                <div class="data-item">
                    <label>Estado:</label>
                    <p>ACTIVO</p>
                </div>
                <div class="data-item">
                    <label>Área asignada:</label>
                    <p>Sistemas</p>
                </div>
                <div class="data-item">
                    <label>Fecha de inicio:</label>
                    <p>28/08/2025</p>
                </div>
                <div class="data-item">
                    <label>Fecha de cierre:</label>
                    <p>N/E</p>
                </div>
                <div class="data-item">
                    <label>Hora de entrada:</label>
                    <p>8:00 am</p>
                </div>
                <div class="data-item">
                    <label>Hora de salida:</label>
                    <p>11:00 am</p>
                </div>
                <div class="data-item">
                    <label>Horas requeridas:</label>
                    <p>252 </p>
                </div>
                <div class="data-item">
                    <label>Horas registradas:</label>
                    <p>27 </p>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/menu_modal.js') }}"></script>
</body>

</html>
