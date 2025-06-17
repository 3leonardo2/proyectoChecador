<!DOCTYPE html>
<html lang="es">

<head>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de practicante</title>
    <link rel="stylesheet" href="{{ asset('css/detailsprac.css') }}">
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
                    <button class="credential-button" id="credentialButton">
                        <i class="fa-solid fa-id-card"></i>
                        Credencial
                    </button>
                </div>
            </div>
            <a href="{{ route('practicantes.edit', $practicante->id_practicante) }}" class="edit-button">
                <i class="fa-solid fa-pen-to-square"></i>
                Editar
            </a>
            <a href="{{ route('evaluaciones.index', $practicante->id_practicante) }}" class="revision-button">
                <i class="fa-solid fa-ranking-star"></i> Ver revisiones
            </a>
            <div class="practicante-scrollable-content">
                <!-- ------------------------------------------------------ -->
                <h2>Datos Generales</h2>
                <div class="data-item">
                    <label>Nombre:</label>
                    <p>{{ $practicante->nombre }}</p>
                </div>
                <div class="data-item">
                    <label>Apellidos:</label>
                    <p>{{ $practicante->apellidos }}</p>
                </div>
                <div class="data-item">
                    <label>Fecha de nacimiento:</label>
                    <p>{{ \Carbon\Carbon::parse($practicante->fecha_nacimiento)->format('d/m/Y') }}</p>
                </div>
                <div class="data-item">
                    <label>Sexo:</label>
                    <p>{{ $practicante->sexo }}</p>
                </div>
                <div class="data-item">
                    <label>Correo electrónico personal:</label>
                    <p>{{ $practicante->email_personal }}</p>
                </div>
                <div class="data-item">
                    <label>Número de teléfono personal:</label>
                    <p>{{ $practicante->telefono_personal }}</p>
                </div>
                <div class="data-item">
                    <label>Contacto de emergencia:</label>
                    <p>{{ $practicante->nombre_emergencia }}</p>
                </div>
                <div class="data-item">
                    <label>Teléfono de emergencia:</label>
                    <p>{{ $practicante->telefono_emergencia }}</p>
                </div>
                <div class="data-item">
                    <label>Número de teléfono personal:</label>
                    <p>{{ $practicante->num_seguro }}</p>
                </div>
                <!-- Continúa con el resto de los campos de la misma manera -->

                <!-- Información institucional -->
                <h2>Información institucional:</h2>
                <div class="data-item">
                    <label>Escuela o institución:</label>
                    <p>{{ $practicante->institucion->nombre }}</p>
                </div>
                <div class="data-item">
                    <label>Carrera:</label>
                    <p>{{ $practicante->carrera->nombre_carr }}</p>
                </div>
                <div class="data-item">
                    <label>Correo de carrera:</label>
                    <p>{{ $practicante->carrera->correo_carr }}</p>
                </div>
                <div class="data-item">
                    <label>Teléfono de carrera:</label>
                    <p>{{ $practicante->carrera->telefono_carr }}</p>
                </div>
                <!-- Continúa con el resto de los campos institucionales -->
                <h2>Información de Prácticas:</h2>
                <div class="data-item">
                    <label>Estado de prácticas:</label>
                    <p>{{ $practicante->estado_practicas }}</p>
                </div>
                <div class="data-item">
                    <label>Área asignada:</label>
                    <p>{{ $practicante->area_asignada }}</p>
                </div>
                <div class="data-item">
                    <label>Fecha de inicio:</label>
                    <p>{{ $practicante->fecha_inicio }}</p>
                </div>
                <div class="data-item">
                    <label>Fecha de finalización:</label>
                    <p>{{ $practicante->fecha_final }}</p>
                </div>
                <div class="data-item">
                    <label>Hora de entrada:</label>
                    <p>{{ $practicante->hora_entrada }}</p>
                </div>
                <div class="data-item">
                    <label>Hora de salida:</label>
                    <p>{{ $practicante->hora_salida }}</p>
                </div>
                <div class="data-item">
                    <label>Fecha de finalización:</label>
                    <p>{{ $practicante->fecha_final }}</p>
                </div>
                <div class="data-item">
                    <label>Horas requeridas:</label>
                    <p>{{ $practicante->horas_requeridas }}</p>
                </div>
                <div class="data-item">
                    <label>Horas acumuladas:</label>
                    <p>{{ $practicante->horas_registradas }}</p>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/menu_modal.js') }}"></script>
</body>

</html>
