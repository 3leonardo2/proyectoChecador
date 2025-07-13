<!DOCTYPE html>
<html lang="es">

<head>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Nuevo Administrador</title>
    <link rel="stylesheet" href="{{ asset('css/detailsprac.css') }}">
    <link rel="stylesheet" href="{{ asset('css/registrar_prac.css') }}">
    <link rel="stylesheet" href="{{ asset('css/editprac.css') }}">
    <link rel="stylesheet" href="{{ asset('css/menu_modal.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Alertas flotantes */
        .floating-alert {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1050;
            min-width: 350px;
            max-width: 90%;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            border: none;
            border-radius: 8px;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            animation: slideDown 0.3s ease-out;
        }

        .floating-alert.success {
            background-color: #3e8951;
            color: white;
        }

        .floating-alert.error {
            background-color: #d9534f;
            color: white;
        }

        .floating-alert.info {
            background-color: #5bc0de;
            color: white;
        }

        .floating-alert .close {
            color: white;
            opacity: 0.8;
            font-size: 1.5rem;
            line-height: 1;
            cursor: pointer;
            background: none;
            border: none;
            padding: 0;
            margin-left: 15px;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateX(-50%) translateY(-30px);
            }

            to {
                opacity: 1;
                transform: translateX(-50%) translateY(0);
            }
        }
    </style>
</head>

<body>
    @include('partials.menu_modal')
    <div class="header">
        <h1>Registrar Nuevo Administrador</h1>
        <button class="menu-button" id="menuButton">
            <i class="fa-solid fa-bars"></i>
        </button>
    </div>

    <div class="main-container">

        @if (session('success'))
            <div class="floating-alert success">
                {{ session('success') }}
                <button type="button" class="close" onclick="this.parentElement.remove()">&times;</button>
            </div>
        @endif

        @if (session('error'))
            <div class="floating-alert error">
                {{ session('error') }}
                <button type="button" class="close" onclick="this.parentElement.remove()">&times;</button>
            </div>
        @endif

        @if (session('credenciales'))
            <div class="floating-alert info">
                <div>
                    <h4>Credenciales generadas:</h4>
                    <p><strong>Correo:</strong> {{ session('credenciales.correo') }}</p>
                    <p><strong>Contraseña:</strong> {{ session('credenciales.contrasena') }}</p>
                    <p class="text-white">¡Guarde estas credenciales! No podrán ser recuperadas después.</p>
                </div>
                <button type="button" class="close" onclick="this.parentElement.remove()">&times;</button>
            </div>
        @endif

        <form action="{{ route('admin.store') }}" method="POST" class="practicante-info-wrapper">
            @csrf
            <div class="practicante-scrollable-content">
                <h2>Datos del Administrador</h2>

                <div class="form-group">
                    <label for="tipo_registro">Tipo de Registro*:</label>
                    <select id="tipo_registro" name="tipo_registro" required>
                        <option value="">Seleccione tipo de registro</option>
                        <option value="manual">Registro manual</option>
                        <option value="automatico">Generar asesor genérico</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="nombre">Nombre*:</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Nombre completo..." required>
                </div>

                <!-- Campos para registro manual -->
                <div id="manual-fields" style="display: none;">
                    <div class="form-group">
                        <label for="correo">Correo electrónico*:</label>
                        <input type="email" id="correo" name="correo" placeholder="Correo electrónico...">
                    </div>

                    <div class="form-group">
                        <label for="contrasena">Contraseña (mínimo 8 caracteres)*:</label>
                        <input type="password" id="contrasena" name="contrasena" placeholder="Contraseña...">
                        <button type="button" class="toggle-password" data-target="contrasena">
                            <i class="fa fa-eye"></i>
                        </button>
                    </div>

                    <div class="form-group">
                        <label for="confirmar_contrasena">Confirmar Contraseña*:</label>
                        <input type="password" id="confirmar_contrasena" name="contrasena_confirmation"
                            placeholder="Repita la contraseña...">
                        <button type="button" class="toggle-password" data-target="confirmar_contrasena">
                            <i class="fa fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label for="departamento">Departamento*:</label>
                    <select id="departamento" name="departamento" required>
                        <option value="">Seleccione un departamento</option>
                        @foreach ($departamentos as $depto)
                            <option value="{{ $depto }}">{{ $depto }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="rol">Rol*:</label>
                    <select id="rol" name="rol" required>
                        <option value="">Seleccione un rol de administrador</option>
                        <option value="rh">Administrador de aplicación</option>
                        <option value="asesor">Asesor de departamento</option>
                    </select>
                </div>

                <div class="form-actions">
                    <button type="submit" class="save-button">Registrar Administrador</button>
                </div>
            </div>
        </form>
    </div>

    <script src="{{ asset('js/menu_modal.js') }}"></script>
    <script>
        // Validación del formulario
        document.querySelector('form').addEventListener('submit', function(e) {
            const tipoRegistro = document.getElementById('tipo_registro').value;

            if (tipoRegistro === 'manual') {
                const password = document.getElementById('contrasena').value;
                const confirm = document.getElementById('confirmar_contrasena').value;
                const regex = /^(?=.*[A-Za-zÁÉÍÓÚáéíóúÑñ])(?=.*\d).{8,}$/;

                if (!regex.test(password)) {
                    alert('La contraseña debe tener al menos 8 caracteres y contener letras y números.');
                    e.preventDefault();
                    return false;
                }
                if (password !== confirm) {
                    alert('Las contraseñas no coinciden.');
                    e.preventDefault();
                    return false;
                }
            }
        });
        document.querySelectorAll('.toggle-password').forEach(btn => {
            btn.addEventListener('click', function() {
                const input = document.getElementById(this.dataset.target);
                if (input.type === "password") {
                    input.type = "text";
                    this.innerHTML = '<i class="fa fa-eye-slash"></i>';
                } else {
                    input.type = "password";
                    this.innerHTML = '<i class="fa fa-eye"></i>';
                }
            });
        });

        document.querySelector('form').addEventListener('submit', function(e) {
            const password = document.getElementById('contrasena').value;
            const confirm = document.getElementById('confirmar_contrasena').value;
            // Cambia esta expresión según tus requisitos de seguridad
            const regex = /^(?=.*[A-Za-zÁÉÍÓÚáéíóúÑñ])(?=.*\d).{8,}$/;

            if (!regex.test(password)) {
                alert('La contraseña debe tener al menos 8 caracteres y contener letras y números.');
                e.preventDefault();
                return false;
            }
            if (password !== confirm) {
                alert('Las contraseñas no coinciden.');
                e.preventDefault();
                return false;
            }
        });

        document.getElementById('tipo_registro').addEventListener('change', function() {
            const manualFields = document.getElementById('manual-fields');
            const rolSelect = document.getElementById('rol');

            if (this.value === 'automatico') {
                manualFields.style.display = 'none';
                rolSelect.value = 'asesor';
                // No lo deshabilitamos, solo lo hacemos de solo lectura

            } else {
                manualFields.style.display = 'block';
                rolSelect.readOnly = false;
            }
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
