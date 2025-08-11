<!DOCTYPE html>
<html lang="es">

<head>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <style>
        .form-pills .form-control {
            border-radius: 50px;
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100 " style="background-color: #e6e8e4;">
    @if ($errors->any())
        <div id="login-error-alert"
            class="alert alert-danger text-center position-fixed top-0 start-50 translate-middle-x mt-3"
            style="z-index: 9999; min-width: 350px;">
            {{ $errors->first() }}
        </div>
    @endif
    @if (session('generic_warning'))
        <div id="generic-warning-alert"
            class="alert alert-warning text-center position-fixed top-0 start-50 translate-middle-x mt-3"
            style="z-index: 9999; min-width: 350px;">
            {{ session('generic_warning') }}
        </div>
    @endif
    <div class="container-fluid px-0 flex-grow-1 d-flex align-items-center ">
        <div class="card shadow w-100">
            <div class="card-body py-5">
                <h2 class="text-center mb-4">Iniciar sesión</h2>
                <form method="POST" action="{{ route('login') }}" class="form-pills mx-auto" style="max-width: 400px;">
                    @csrf
                    <div class="mb-3">
                        <label for="correo" class="form-label">Nombre de usuario o Correo</label>
                        <input type="text" class="form-control" name="correo" id="correo"
                            placeholder="tu@correo.com" required>
                    </div>

                    <div class="mb-4">
                        <label for="contrasena" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" name="contrasena" id="contrasena"
                            placeholder="••••••••" required>
                    </div>

                    <button type="submit" class="btn btn-custom w-100 rounded-pill py-2">
                        Iniciar Sesión
                    </button>

                    <div class="text-center mt-3">
                        <a class="btn btn-secondary btn-lg" style="font-size: 1.1rem;"
                            href="{{ route('bitacora.index') }}">
                            Ir a bitácora de practicantes
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const alert = document.getElementById('login-error-alert');
            if (alert) {
                setTimeout(() => {
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                }, 5000);
            }
        });
    </script>
</body>

</html>
