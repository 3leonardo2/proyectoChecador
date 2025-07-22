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
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    <div class="header">
        <a href="{{ route('practicantes.show', parameters: $practicante->id_practicante) }}" class="back-button">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1>Editar Practicante</h1>
    </div>

    <div class="main-container">
<form action="{{ route('practicantes.update', $practicante->id_practicante) }}" method="POST"
      class="practicante-info-wrapper" enctype="multipart/form-data"
      data-carrera-route="{{ route('practicantes.getByCarrera') }}">
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
<div class="form-group institution-select-container">
    <label for="institucion_select">Institución:</label>
    <select name="institucion_id" id="institucion_select" class="form-control">
        <option value="">Seleccione una institución</option>
        @foreach($instituciones as $institucion)
            <option value="{{ $institucion->id_institucion }}"
                {{ old('institucion_id', $practicante->institucion_id) == $institucion->id_institucion ? 'selected' : '' }}>
                {{ $institucion->nombre }}
            </option>
        @endforeach
    </select>
</div>

<div class="form-group carrera-select-container">
    <label for="carrera_select">Carrera:</label>
    <select name="carrera_id" id="carrera_select" class="form-control" disabled>
        <option value="">Primero seleccione una institución</option>
        {{-- Aquí se cargarán las carreras dinámicamente --}}
        @if ($practicante->carrera) {{-- Si ya tiene una carrera, precargarla --}}
            <option value="{{ $practicante->carrera->id_carrera }}" selected>
                {{ $practicante->carrera->nombre_carr }}
            </option>
        @endif
    </select>
    <div class="loading-carreras" style="display: none;">Cargando carreras...</div>
    <div class="no-carreras-message" style="display: none; color: #dc3545;">No hay carreras disponibles para esta institución.</div>
</div>

<div class="form-group">
    <label for="email_institucional">Correo Institucional:</label>
    <input type="email" id="email_institucional" name="correo_institucional_carrera" class="form-control"
           value="{{ old('correo_institucional_carrera', $practicante->carrera->correo_carr ?? '') }}" readonly>
</div>
<div class="form-group">
    <label for="telefono_institucional">Teléfono Institucional:</label>
    <input type="text" id="telefono_institucional" name="telefono_institucional_carrera" class="form-control"
           value="{{ old('telefono_institucional_carrera', $practicante->carrera->tel_gerente ?? '') }}" readonly>
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
                <h2>Información del Proyecto (Opcional)</h2>
<div class="form-group">
    <input type="checkbox" id="incluir_proyecto" name="incluir_proyecto"
           {{ old('incluir_proyecto', $practicante->proyecto_id ? 'on' : '') == 'on' ? 'checked' : '' }}>
    <label for="incluir_proyecto">Asignar a un proyecto</label>
</div>

<div id="proyecto_fields" style="display: none;">
    <div class="form-group">
        <label for="nombre_proyecto">Nombre del Proyecto:</label>
        <input type="text" id="nombre_proyecto" name="nombre_proyecto" class="form-control"
               value="{{ old('nombre_proyecto', $practicante->proyecto->nombre_proyecto ?? '') }}">
    </div>
    <div class="form-group">
        <label for="descripcion_proyecto">Descripción del Proyecto:</label>
        <textarea id="descripcion_proyecto" name="descripcion_proyecto" class="form-control">{{ old('descripcion_proyecto', $practicante->proyecto->descripcion_proyecto ?? '') }}</textarea>
    </div>
    <div class="form-group">
                    <label for="area_proyecto">Área asignada:</label>
                    <select id="area_proyecto" name="area_proyecto">
                        <option value="">Seleccione una opción</option>
                        <option value="Contraloria">Contraloria</option>
                        <option value="Ventas">Ventas</option>
                        <option value="Sistemas">Sistemas</option>
                        <option value="AyB">AyB</option>
                        <option value="Mantenimiento">Mantenimiento</option>
                        <option value="Recursos Humanos">Recursos Humanos</option>
                        <option value="Dirección">Dirección</option>
                        <option value="Recepción">Recepción</option>
                        <option value="Reservaciones">Reservaciones</option>
                        <option value="Cocina">Cocina</option>
                        <option value="Ama de llaves">Ama de llaves</option>
                    </select>
    </div>
</div>
                <div class="form-actions">
                    <button type="submit" class="save-button">Guardar cambios</button>
                    <button type="button" class="cancel-button">Cancelar</button>
                </div>
            </div>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="{{ asset('js/fotosPrac_logica.js') }}"></script>
    <script src="{{ asset('js/edit_prac.js') }}"></script>
    <script src="{{ asset('js/getInfoCarrera.js') }}"></script>
<script>
    // Lógica para mostrar/ocultar campos de proyecto
    document.addEventListener('DOMContentLoaded', function() {
        const incluirProyectoCheckbox = document.getElementById('incluir_proyecto');
        const proyectoFields = document.getElementById('proyecto_fields');

        // Función para actualizar la visibilidad y requerimiento de los campos
        const updateProyectoFieldsVisibility = () => {
            if (incluirProyectoCheckbox.checked) {
                proyectoFields.style.display = 'block';
                // Puedes hacer que los campos sean requeridos aquí si lo deseas
            } else {
                proyectoFields.style.display = 'none';
                // Limpiar campos al ocultar (importante para que no se envíen datos viejos si se desmarca)
                document.getElementById('nombre_proyecto').value = '';
                document.getElementById('descripcion_proyecto').value = '';
                document.getElementById('area_proyecto').value = '';
            }
        };

        incluirProyectoCheckbox.addEventListener('change', updateProyectoFieldsVisibility);

        // Llamar la función al cargar la página para reflejar el estado inicial
        updateProyectoFieldsVisibility();

        // Si hay errores de validación y los campos estaban visibles, mantenerlos así
        @if($errors->hasAny(['nombre_proyecto', 'descripcion_proyecto', 'area_proyecto']) || old('incluir_proyecto'))
            incluirProyectoCheckbox.checked = true;
            proyectoFields.style.display = 'block';
        @endif
    });
</script>
</body>

</html>
