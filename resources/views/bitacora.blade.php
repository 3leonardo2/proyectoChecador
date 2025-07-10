<!DOCTYPE html>
<html lang="es">

<head>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bitácora de Practicantes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bitacora.css') }}">

    <style>
        .welcome-message {
            animation: fadeOut 5s forwards;
            animation-delay: 5s;
            opacity: 1;
        }

        @keyframes fadeOut {
            to {
                opacity: 0;
                height: 0;
                overflow: hidden;
                margin: 0;
            }
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100" style="background-color: #e6e8e4;">
    @include('partials.detalles_modal')

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
                        <form id="bitacora-form" method="POST" action="{{ route('bitacora.registrar') }}">
                            @csrf
                            <input type="text" name="codigo" id="codigo-input"
                                class="form-control rounded-pill py-2" placeholder="Ingresa tu código..." required
                                autofocus>
                            <input type="hidden" name="tipo" id="tipo-evento">
                        </form>
                    </div>

                    <div class="d-flex justify-content-center">
                        <div class="d-flex gap-1">
                            <button type="button" class="btn btn-custom rounded-pill py-2 px-3"
                                onclick="registrarEvento('entrada')">
                                Entrada
                            </button>
                            <button type="button" class="btn btn-custom rounded-pill py-2 px-3"
                                onclick="registrarEvento('entrada_comedor')">
                                Entrada comedor
                            </button>
                            <button type="button" class="btn btn-custom rounded-pill py-2 px-3"
                                onclick="registrarEvento('salida_comedor')">
                                Salida comedor
                            </button>
                            <button type="button" class="btn btn-custom rounded-pill py-2 px-3"
                                onclick="registrarEvento('salida')">
                                Salida
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="text-center" id="welcome-container">
            @if (session()->has('welcome_message'))
                <h1 class="welcome-message">
                    @if (session('welcome_message.genero') === '@')
                        ¡BIENVENID@ {{ strtoupper(session('welcome_message.nombre')) }}!
                    @else
                        BIENVENID{{ session('welcome_message.genero') }}
                        {{ strtoupper(session('welcome_message.nombre')) }}
                    @endif
                </h1>
                @php
                    session()->forget('welcome_message');
                @endphp
            @else
                <h1>BIENVENID@</h1>
            @endif
        </div>
        <div style="text-align: right;">
            <a href="{{ route('consulta_horas') }}" type="button" class="btn btn-custom2 rounded-pill py-2 px-3">
                Consulta tus horas
            </a>
        </div>
    </div>

    <div class="image-avisos-wrapper">
        <div class="imagen-container">
            <div id="carouselAvisos" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner" id="carousel-inner-avisos">
                    @foreach ($imagenesAvisos as $index => $imagen)
                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}"
                            data-bs-interval="{{ $imagen->duracion * 1000 }}">
                            <img src="{{ $imagen->ruta }}" class="d-block w-100" alt="{{ $imagen->titulo }}"
                                style="max-height: 200px; object-fit: contain;">
                            @if ($imagen->titulo || $imagen->descripcion)
                                <div class="carousel-caption d-none d-md-block">
                                    @if ($imagen->titulo)
                                        <h5>{{ $imagen->titulo }}</h5>
                                    @endif
                                    @if ($imagen->descripcion)
                                        <p>{{ $imagen->descripcion }}</p>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
                @if (count($imagenesAvisos) > 1)
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselAvisos"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselAvisos"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                @endif
            </div>
        </div>
        <div class="avisos-container">
            <h5>Avisos</h5>

            {{-- Verificación de avisos --}}
            @if ($avisos->count() > 0)
                @foreach ($avisos as $aviso)
                    <div class="aviso-item">
                        <p>- {{ $aviso->contenido }}</p>
                        <small class="text-muted">
                            Vigente hasta: {{ $aviso->fecha_fin->format('d/m/Y H:i') }}
                        </small>
                    </div>
                @endforeach
            @else
                <p class="text-muted">No hay avisos por el momento</p>
            @endif

            {{-- Depuración: Mostrar datos recibidos --}}
            @auth
                @if (auth()->user()->isAdmin())
                    <div class="mt-3 p-2 bg-light rounded">
                        <small class="text-muted">DEBUG:</small>
                        <pre class="small mb-0">{{ json_encode($avisos, JSON_PRETTY_PRINT) }}</pre>
                    </div>
                @endif
            @endauth
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
    <script>
        function registrarEvento(tipo) {
            document.getElementById('tipo-evento').value = tipo;
            fetch(document.getElementById('bitacora-form').action, {
                    method: 'POST',
                    body: new FormData(document.getElementById('bitacora-form')),
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => {
                            throw err;
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    showModal(data.success ? 'Éxito' : 'Error', data.message, data.success);
                    if (tipo === 'entrada' && data.success) {
                        window.location.reload(); // Recarga solo para entrada (para mostrar mensaje de bienvenida)
                    }
                })
                .catch(error => {
                    showModal('Error', error.message || 'Error inesperado', false);
                });
        }

        function showModal(title, message, isSuccess) {
            const modal = document.getElementById('alertModal');
            const icon = document.getElementById('alertModalIcon');
            const msg = document.getElementById('alertModalMessage');

            // Configura el modal según éxito/error
            modal.className = `alert-modal ${isSuccess ? 'success' : 'error'}`;
            icon.innerHTML = isSuccess ?
                '<i class="fas fa-check-circle"></i>' :
                '<i class="fas fa-exclamation-circle"></i>';
            msg.textContent = message;

            // Muestra el modal con animación
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('codigo-input');
            input.focus();

            document.getElementById('bitacora-form').addEventListener('submit', function(e) {
                e.preventDefault();
                fetch('{{ route('bitacora.registrar.automatico') }}', {
                        method: 'POST',
                        body: new FormData(this),
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        showModal(data.success ? 'Éxito' : 'Error', data.message, data.success);
                        input.value = ''; // Limpiar input
                        input.focus();
                        // Si quieres recargar para mostrar bienvenida, puedes hacerlo aquí si data.tipo_registrado === 'entrada'
                    })
                    .catch(error => {
                        showModal('Error', error.message || 'Error inesperado', false);
                        input.value = '';
                        input.focus();
                    });
            });

            input.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    document.getElementById('bitacora-form').dispatchEvent(new Event('submit'));
                }
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
