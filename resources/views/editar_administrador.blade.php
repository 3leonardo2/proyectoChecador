<!DOCTYPE html>
<html lang="es">

<head>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Administrador</title>
    <link rel="stylesheet" href="{{ asset('css/detailsprac.css') }}">
    <link rel="stylesheet" href="{{ asset('css/registrar_prac.css') }}">
    <link rel="stylesheet" href="{{ asset('css/editprac.css') }}">
    <link rel="stylesheet" href="{{ asset('css/menu_modal.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

</head>

<body>
    <div class="header">
        <a href="{{ route('administradores.lista') }}" class="back-button">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1>Editar Administrador</h1>
    </div>
    <div class="main-container">
        <form action="{{ route('administradores.update', $admin->id_admin) }}" method="POST"
            class="practicante-info-wrapper">
            @csrf
            @method('PUT')
            <div class="practicante-scrollable-content">

                <div class="form-group">
                    <label for="nombre">Nombre*:</label>
                    <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $admin->nombre) }}"
                        required>
                </div>

                <div class="form-group">
                    <label for="correo">Correo electrónico*:</label>
                    <input type="email" id="correo" name="correo" value="{{ old('correo', $admin->correo) }}"
                        required>
                </div>

                <div class="form-group">
                    <label for="departamento">Departamento*:</label>
                    <select id="departamento" name="departamento" required>
                        <option value="">Seleccione un departamento</option>
                            <option value="Contraloria" {{ old('departamento', $admin->departamento) == 'Contraloria' ? 'selected' : '' }}>
                                Contraloria</option>
                            <option value="Ventas" {{ old('departamento', $admin->departamento) == 'Ventas' ? 'selected' : '' }}>
                                Ventas</option>
                            <option value="Sistemas" {{ old('departamento', $admin->departamento) == 'Sistemas' ? 'selected' : '' }}>
                                Sistemas</option>
                            <option value="AyB" {{ old('departamento', $admin->departamento) == 'AyB' ? 'selected' : '' }}>
                                AyB</option>
                            <option value="Mantenimiento" {{ old('departamento', $admin->departamento) == 'Mantenimiento' ? 'selected' : '' }}>
                                Mantenimiento</option>
                            <option value="Recursos Humanos" {{ old('departamento', $admin->departamento) == 'Recursos Humanos' ? 'selected' : '' }}>
                                Recursos Humanos</option>
                            <option value="Dirección" {{ old('departamento', $admin->departamento) == 'Dirección' ? 'selected' : '' }}>
                                irección</option>
                            <option value="Recepción" {{ old('departamento', $admin->departamento) == 'Recepción' ? 'selected' : '' }}>
                                Recepción</option>
                            <option value="Reservaciones" {{ old('departamento', $admin->departamento) == 'Reservaciones' ? 'selected' : '' }}>
                                Reservaciones</option>
                            <option value="Cocina" {{ old('departamento', $admin->departamento) == 'Cocina' ? 'selected' : '' }}>
                                Cocina</option>
                            <option value="Ama de llaves" {{ old('departamento', $admin->departamento) == 'Ama de llaves' ? 'selected' : '' }}>
                                Ama de llaves</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="rol">Rol*:</label>
                    <select id="rol" name="rol" required>
                        <option value="">Seleccione un rol de administrador</option>
                        <option value="rh" {{ old('rol', $admin->rol) == 'rh' ? 'selected' : '' }}>Administrador de
                            aplicación</option>
                        <option value="asesor" {{ old('rol', $admin->rol) == 'asesor' ? 'selected' : '' }}>Asesor de
                            departamento</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="contrasena">Nueva Contraseña (mínimo 8 caracteres)*:</label>
                    <input type="password" id="contrasena" name="contrasena" placeholder="Contraseña...">
                    <button type="button" class="toggle-password" data-target="contrasena">
                        <i class="fa fa-eye"></i>
                    </button>
                </div>

                <div class="form-group">
                    <label for="confirmar_contrasena">Confirmar Nueva Contraseña*:</label>
                    <input type="password" id="confirmar_contrasena" name="contrasena_confirmation"
                        placeholder="Repita la contraseña...">
                    <button type="button" class="toggle-password" data-target="confirmar_contrasena">
                        <i class="fa fa-eye"></i>
                    </button>
                </div>

                <div class="form-actions">
                    <button type="submit" class="save-button">Guardar Cambios</button>
                    <a href="{{ route('administradores.lista') }}" class="cancel-button">Cancelar</a>
                </div>
            </div>
        </form>
    </div>

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
