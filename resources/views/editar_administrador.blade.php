<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Administrador</title>
    <link rel="stylesheet" href="{{ asset('css/detailsprac.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/editprac.css') }}">
    <link rel="stylesheet" href="{{ asset('css/menu_modal.css') }}">
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
                        <option value="Recursos Humanos"
                            {{ old('departamento', $admin->departamento) == 'Recursos Humanos' ? 'selected' : '' }}>
                            Recursos Humanos</option>
                        <option value="Sistemas"
                            {{ old('departamento', $admin->departamento) == 'Sistemas' ? 'selected' : '' }}>Sistemas
                        </option>
                        <option value="Administración"
                            {{ old('departamento', $admin->departamento) == 'Administración' ? 'selected' : '' }}>
                            Administración</option>
                        <option value="Direccion"
                            {{ old('departamento', $admin->departamento) == 'Direccion' ? 'selected' : '' }}>Dirección
                        </option>
                        <option value="Cocina"
                            {{ old('departamento', $admin->departamento) == 'Cocina' ? 'selected' : '' }}>Cocina
                        </option>
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
                    <label for="contrasena">Nueva Contraseña:</label>
                    <input type="password" id="contrasena" name="contrasena"
                        placeholder="Dejar en blanco para no cambiar">
                </div>

                <div class="form-group">
                    <label for="contrasena_confirmation">Confirmar Nueva Contraseña:</label>
                    <input type="password" id="contrasena_confirmation" name="contrasena_confirmation"
                        placeholder="Repita la nueva contraseña">
                </div>

                <div class="form-actions">
                    <button type="submit" class="save-button">Guardar Cambios</button>
                    <a href="{{ route('administradores.lista') }}" class="cancel-button">Cancelar</a>
                </div>
            </div>
        </form>
    </div>
</body>

</html>
