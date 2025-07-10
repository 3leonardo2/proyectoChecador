<!DOCTYPE html>
<html lang="es">

<head>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Practicante</title>
    <link rel="stylesheet" href="{{ asset('css/detailsprac.css') }}">
    <link rel="stylesheet" href="{{ asset('css/editprac.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <div class="header">
        <a href="{{ route('practicantes.show', parameters: $practicante->id_practicante) }}" class="back-button">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1>Editar Practicante</h1>
    </div>

    <div class="main-container">
        <form action="{{ route('practicantes.update', $practicante->id_practicante) }}" method="POST"
            class="practicante-info-wrapper" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="practicante-fixed-elements">
                <div class="practicante-profile-section">
                    @if ($practicante->profile_image && Storage::disk('public')->exists($practicante->profile_image))
                        <div class="profile-image-container" id="image-preview-container">
                            <img src="{{ asset('storage/' . $practicante->profile_image) }}" alt="ImagenPracticante"
                                class="profile-image">
                        </div>
                    @else
                        <div class="profile-image-container" id="image-preview-container">
                            <i class="fas fa-user-circle default-avatar-icon"></i>
                        </div>
                    @endif
                    <input type="file" id="add-image-input" class="add-image-input" name="profile_image"
                        accept="image/*" style="display: none;">
                    <label for="add-image-input" class="add-image-button">Cambiar imagen...</label>
                    <div class="practicante-codigo">Código: {{ $practicante->codigo }}</div>
                </div>
            </div>

            <div class="practicante-scrollable-content">
                <h2>Datos Generales</h2>
                <div class="form-group">
                    <label for="nombre">Nombre*:</label>
                    <input type="text" id="nombre" name="nombre" required
                        value="{{ old('nombre', $practicante->nombre) }}">
                </div>
                <div class="form-group">
                    <label for="apellidos">Apellidos*:</label>
                    <input type="text" name="apellidos" value="{{ old('apellidos', $practicante->apellidos) }}">
                </div>
                <div class="form-group">
                    <label for="fecha_nacimiento">Fecha de nacimiento*:</label>
                    <input type="date" name="fecha_nacimiento"
                        value="{{ old('fecha_nacimiento', $practicante->fecha_nacimiento) }}">
                </div>
                <div class="form-group">
                    <label for="curp">CURP*:</label>
                    <input type="text" id="curp" name="curp" required
                        value="{{ old('curp', $practicante->curp) }}">
                </div>
                <div class="form-group">
                    <label for="sexo">Sexo:</label>
                    <select id="sexo" name="sexo">
                        <option value="">Seleccione una opción</option>
                        <option value="Hombre" {{ $practicante->sexo == 'Hombre' ? 'selected' : '' }}>Hombre</option>
                        <option value="Mujer" {{ $practicante->sexo == 'Mujer' ? 'selected' : '' }}>Mujer</option>
                        <option value="Otro" {{ $practicante->sexo == 'Otro' ? 'selected' : '' }}>Otro</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="direccion">Dirección:</label>
                    <input type="text" name="direccion" value="{{ old('direccion', $practicante->direccion) }}">
                </div>
                <div class="form-group">
                    <label for="email_personal">Correo electrónico personal:</label>
                    <input type="email" name="email_personal"
                        value="{{ old('email_personal', $practicante->email_personal) }}">
                </div>
                <div class="form-group">
                    <label for="telefono_personal">Teléfono personal:</label>
                    <input type="number" name="telefono_personal"
                        value="{{ old('telefono_personal', $practicante->telefono_personal) }}">
                </div>
                <div class="form-group">
                    <label for="nombre_emergencia">Nombre de emergencia:</label>
                    <input type="text" name="nombre_emergencia"
                        value="{{ old('nombre_emergencia', $practicante->nombre_emergencia) }}">
                </div>
                <div class="form-group">
                    <label for="telefono_emergencia">Teléfono de emergencia:</label>
                    <input type="number" name="telefono_emergencia"
                        value="{{ old('telefono_emergencia', $practicante->telefono_emergencia) }}">

                </div>
                <div class="form-group">
                    <label for="num_seguro">Número de seguro:</label>
                    <input type="text" name="num_seguro" 
                        value="{{ old('num_seguro', $practicante->num_seguro) }}">
                </div>

                <h2>Información institucional:</h2>
                <div class="form-group">
                    <label for="institucion_nombre">Escuela o institución:</label>
                    <input type="text" name="institucion_nombre"
                        value="{{ old('institucion_nombre', optional($practicante->institucion)->nombre) }}">

                </div>
                <div class="form-group">
                    <label for="carrera_nombre">Carrera:</label>
                    <input type="text" name="carrera_nombre"
                        value="{{ old('carrera_nombre', optional($practicante->carrera)->nombre_carr) }}">

                </div>
                <div class="form-group">
                    <label for="email_institucional">Correo institucional:</label>
                    <input type="email" name="email_institucional"
                        value="{{ old('email_institucional', $practicante->email_institucional) }}">
                </div>

                <div class="form-group">
                    <label for="telefono_institucional">Teléfono institucional:</label>
                    <input type="text" name="telefono_institucional"
                        value="{{ old('telefono_institucional', $practicante->telefono_institucional) }}">
                </div>
                <div class="form-group">
                    <label for="nivel_estudios">Nivel de estudios:</label>
                    <select id="nivel_estudios" name="nivel_estudios">
                        <option value="">Seleccione una opción</option>
                        <option value="Bachillerato"
                            {{ $practicante->nivel_estudios == 'Bachillerato' ? 'selected' : '' }}> Bachillerato
                        </option>
                        <option value="Licenciatura"
                            {{ $practicante->nivel_estudios == 'Licenciatura' ? 'selected' : '' }}> Licenciatura
                        </option>
                        <option value="Técnico" {{ $practicante->nivel_estudios == 'Técnico' ? 'selected' : '' }}>
                            Técnico</option>
                        <option value="Maestría" {{ $practicante->nivel_estudios == 'Maestría' ? 'selected' : '' }}>
                            Maestría</option>
                        <option value="Doctorado" {{ $practicante->nivel_estudios == 'Doctorado' ? 'selected' : '' }}>
                            Doctorad</option>

                    </select>

                </div>

                <h2>Información de prácticas:</h2>
                <div class="form-group">
                    <label for="estado_practicas">Estado:</label>
                    <select id="estado_practicas" name="estado_practicas">
                        <option value="">Seleccione una opción</option>
                        <option value="ACTIVO" {{ $practicante->estado_practicas == 'ACTIVO' ? 'selected' : '' }}>
                            ACTIVO</option>
                        <option value="INACTIVO" {{ $practicante->estado_practicas == 'INACTIVO' ? 'selected' : '' }}>
                            INACTIVO</option>
                        <option value="SUSPENDIDO"
                            {{ $practicante->estado_practicas == 'SUSPENDIDO' ? 'selected' : '' }}>SUSPENDIDO</option>
                        <option value="FINALIZADO"
                            {{ $practicante->estado_practicas == 'FINALIZADO' ? 'selected' : '' }}>FINALIZADO</option>

                    </select>
                </div>
                <div class="form-group">
                    <label for="area_asignada">Área asignada:</label>
                    <input type="text" name="area_asignada" 
                        value="{{ old('area_asignada', default: $practicante->area_asignada) }}">
                </div>
                <div class="form-group">
                    <label for="fecha_inicio">Fecha de inicio:</label>
                    <input type="date" name="fecha_inicio" 
                        value="{{ old('fecha_inicio', default: $practicante->fecha_inicio) }}">
                </div>
                <div class="form-group">
                    <label for="fecha_cierre">Fecha de cierre:</label>
                    <input type="date" name="fecha_final" 
                        value="{{ old('fecha_final', $practicante->fecha_final) }}">
                </div>
                <div class="form-group">
                    <label for="hora_entrada">Hora de entrada:</label>
                    <input type="time" name="hora_entrada" 
                        value="{{ old('hora_entrada', $practicante->hora_entrada) }}">
                </div>
                <div class="form-group">
                    <label for="hora_salida">Hora de salida:</label>
                    <input type="time" name="hora_salida" 
                        value="{{ old('hora_salida', $practicante->hora_salida) }}">
                </div>
                <div class="form-group">
                    <label for="horas_requeridas">Horas requeridas:</label>
                    <input type="number" name="horas_requeridas" 
                        value="{{ old('horas_requeridas', $practicante->horas_requeridas) }}">
                </div>
                <div class="form-group">
                    <label for="horas_registradas">Horas registradas:</label>
                    <input type="number" name="horas_registradas" 
                        value="{{ old('horas_registradas', $practicante->horas_registradas) }}">
                </div>

                <div class="form-actions">
                    <button type="submit" class="save-button">Guardar cambios</button>
                    <button type="button" class="cancel-button">Cancelar</button>
                </div>
            </div>
        </form>
    </div>
    <script src="{{ asset('js/fotosPrac_logica.js') }}"></script>

</body>

</html>
