<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Nuevo Administrador</title>
    <link rel="stylesheet" href="{{ asset('css/detailsprac.css') }}">
    <link rel="stylesheet" href="{{ asset('css/registrar_prac.css') }}">
    <link rel="stylesheet" href="{{ asset('css/editprac.css') }}">
    <link rel="stylesheet" href="{{ asset('css/menu_modal.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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
        <form action="{{ route('administradores.store') }}" method="POST" class="practicante-info-wrapper">
            @csrf
            <div class="practicante-scrollable-content">
                <h2>Datos del Administrador</h2>

                <div class="form-group">
                    <label for="nombre">Nombre*:</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Nombre completo..." required>
                </div>

                <div class="form-group">
                    <label for="correo">Correo electrónico*:</label>
                    <input type="email" id="correo" name="correo" placeholder="Correo electrónico..." required>
                </div>

                <div class="form-group">
                    <label for="contrasena">Contraseña (mínimo 8 caracteres)*:</label>
                    <input type="password" id="contrasena" name="contrasena" placeholder="Contraseña..." required>
                    <button type="button" class="toggle-password" data-target="contrasena">
                        <i class="fa fa-eye"></i>
                    </button>
                </div>

                <div class="form-group">
                    <label for="confirmar_contrasena">Confirmar Contraseña*:</label>
                    <input type="password" id="confirmar_contrasena" name="contrasena_confirmation"
                        placeholder="Repita la contraseña..." required>
                    <button type="button" class="toggle-password" data-target="confirmar_contrasena">
                        <i class="fa fa-eye"></i>
                    </button>
                </div>

                <div class="form-group">
                    <label for="departamento">Departamento*:</label>
                    <select id="departamento" name="departamento" required>
                        <option value="">Seleccione un departamento</option>
                        <option value="Recursos Humanos">Recursos Humanos</option>
                        <option value="Sistemas">Sistemas</option>
                        <option value="Administración">Administración</option>
                        <option value="Direccion">Dirección</option>
                        <option value="Cocina">Cocina</option>
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
    </script>
</body>

</html>
