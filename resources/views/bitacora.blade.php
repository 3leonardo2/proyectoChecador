<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bitácora de Practicantes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <style>
        .bitacora-container {
            width: 100%;
            /* Ocupar todo el ancho */
        }

        /* Eliminar bordes redondeados de la tarjeta */
        .card {
            border-radius: 0 !important;
        }

        .image-avisos-wrapper {
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            width: 100%;
            padding: 1rem;
            align-items: flex-start;
            margin-top: 20px;
            /* Aumenta este valor para bajar el contenido */
            min-height: 200px;
            /* Altura mínima para el área de la imagen */
        }

        .imagen-container {
            width: 260px;
            /* Aumenté el ancho */
            height: 300px;
            /* Aumenté el alto */
            overflow: visible;
            grid-column: 2;
            margin: 0 auto;
            position: relative;
            top: -30px;
            /* Ajusta para subir/bajar la imagen */
        }

        .imagen-container img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            object-position: center;
        }

        .avisos-container {
            text-align: right;
            color: #555;
            grid-column: 3;
            /* Coloca los avisos en la tercera columna (derecha) */
            justify-self: end;
            /* Alinea el contenido a la derecha */
            padding-right: 1rem;
            /* Un poco de espacio del borde derecho */
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100" style="background-color: #e6e8e4;">
    <div class="px-0 flex-grow-1 d-flex align-items-center">
        <div class="col-md-8 col-lg-6 bitacora-container">
            <div class="card shadow">
                <div class="card-body py-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="mb-0">Bitácora</h2>
                        <div class="text-muted fs-6"> <span id="current-time"></span> |
                            <span id="current-date"></span>
                        </div>
                    </div>

                    <div class="mb-3 mx-auto" style="max-width: 400px;"> 
                        <input type="text" class="form-control rounded-pill py-2" placeholder="Ingresa tu código..."> 
                    </div>

                    <div class="d-flex justify-content-center">
                        <div class="d-flex gap-1"> <button type="button" class="btn btn-custom rounded-pill py-2 px-3">
                                Entrada
                            </button>
                            <button type="button" class="btn btn-custom rounded-pill py-2 px-3">
                                Entrada comedor
                            </button>
                            <button type="button" class="btn btn-custom rounded-pill py-2 px-3">
                                Salida comedor
                            </button>
                            <button type="button" class="btn btn-custom rounded-pill py-2 px-3">
                                Salida
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="text-center">
            <h1>Bienvenido UPPER !</h1>
        </div>
        <div style="text-align: right;">
            <a href="/consulta_horas" type="button" class="btn btn-custom2 rounded-pill py-2 px-3">
                Consulta tus horas
            </a>
        </div>
    </div>

    <div class="image-avisos-wrapper">
        <div class="imagen-container">
            <img src="{{ asset('images/felizcum.jfif') }}" alt="Feliz Cumpleaños" style="max-width: 200px;">
        </div>
        <div class="avisos-container">
            <h5>Avisos</h5>
            <p>- Hoy es cumpleaños de Ángel Hernán !</p>
        </div>
    </div>

    <script>
        function updateDateTime() {
            const now = new Date();

            // Formatear hora
            const hours = now.getHours().toString().padStart(2, '0');
            const minutes = now.getMinutes().toString().padStart(2, '0');
            const ampm = hours >= 12 ? 'pm' : 'am';
            const displayHours = hours % 12 || 12;

            // Formatear fecha
            const day = now.getDate().toString().padStart(2, '0');
            const month = (now.getMonth() + 1).toString().padStart(2, '0');
            const year = now.getFullYear();

            // Actualizar elementos
            document.getElementById('current-time').textContent = `${displayHours}:${minutes} ${ampm}`;
            document.getElementById('current-date').textContent = `${day}/${month}/${year}`;
        }

        // Actualizar inmediatamente y cada minuto
        updateDateTime();
        setInterval(updateDateTime, 60000);
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
