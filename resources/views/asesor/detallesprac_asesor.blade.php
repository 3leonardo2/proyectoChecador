<!DOCTYPE html>
<html lang="es">

<head>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de practicante</title>
    <link rel="stylesheet" href="{{ asset('css/detailsprac.css') }}">
    <link rel="stylesheet" href="{{ asset('css/menu_modal.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <div class="header">
        <a href="/asesor/practicantes" class="back-button">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1>Detalles de practicante</h1>
    </div>
    <div class="main-container">
        <div class="practicante-info-wrapper">
            <div class="practicante-fixed-elements">
                <div class="practicante-profile-section">
                    <div class="profile-image-container">
                        @if ($practicante->profile_image && Storage::disk('public')->exists($practicante->profile_image))
                            <img src="{{ asset('storage/' . $practicante->profile_image) }}" alt="Foto del practicante"
                                class="profile-image">
                        @else
                            <div class="default-avatar">
                                <i class="fas fa-user-circle"></i>
                            </div>
                        @endif

                    </div>
                    <div class="practicante-codigo">Código: {{ $practicante->codigo }}</div>
                </div>
            </div>
            <a href="{{ route('asesor.practicantes.evaluaciones', $practicante->id_practicante) }}"
                class="revision-button">
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                showAlertModal('success', '{{ session('success') }}');
            @endif

            @if (session('error'))
                showAlertModal('error', '{{ session('error') }}');
            @endif
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
