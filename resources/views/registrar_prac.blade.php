<!DOCTYPE html>
<html lang="es">

<head>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Nuevo Practicante</title>
    <link rel="stylesheet" href="{{ asset('css/detailsprac.css') }}">
    <link rel="stylesheet" href="{{ asset('css/editprac.css') }}">
    <link rel="stylesheet" href="{{ asset('css/registrar_prac.css') }}">
    <link rel="stylesheet" href="{{ asset('css/menu_modal.css') }}">
    <style>
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1000;
            width: 90%;
            max-width: 600px;
            text-align: center;
        }

        .alert-danger {
            color: #a94442;
            background-color: #f2dede;
            border-color: #ebccd1;
        }

        .alert-success {
            color: #3c763d;
            background-color: #dff0d8;
            border-color: #d6e9c6;
        }

        .institution-select-container,
        .carrera-select-container {
            position: relative;
            margin-bottom: 15px;
        }

        .loading-carreras {
            position: absolute;
            color: #666;
        }

        .no-carreras-message {
            color: #dc3545;
            margin-top: 5px;
            font-size: 0.875em;
        }

        select.form-control {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            background-color: white;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    @include('partials.menu_modal')
    <div class="header">
        <h1>Registrar Nuevo Practicante</h1>
        <button class="menu-button" id="menuButton">
            <i class="fa-solid fa-bars"></i>
        </button>
    </div>
    @include('partials.detalles_modal')
    <div class="main-container">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <form action="{{ route('practicantes.store') }}" 
              method="POST" class="practicante-info-wrapper"
              enctype="multipart/form-data" 
              data-carrera-route="{{ route('practicantes.getByCarrera') }}"
              data-old-institucion-id="{{ old('institucion_id') }}"
              data-old-carrera-id="{{ old('carrera_id') }}"
        >
            @csrf <div class="practicante-fixed-elements">
                <div class="practicante-profile-section">
                    <div class="profile-image-container"></div>
                    <input type="file" id="add-image-input" class="add-image-input" name="profile_image"
                        accept="image/jpeg, image/png, image/jpg, image/gif" onchange="validateImage(this)">
                    <label for="add-image-input" class="add-image-button">Añadir imagen...</label>
                    <small class="text-muted">Recomendado: imagen cuadrada (300x300 px, JPG o PNG, 2MB)</small>
                </div>
            </div>

            <div class="practicante-scrollable-content">
                <h2>Datos Generales</h2>
                <div class="form-group">
                    <label for="nombre">Nombre*:</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Nombre..." required
                        value="{{ old('nombre') }}">
                </div>
                <div class="form-group">
                    <label for="apellidos">Apellidos*:</label>
                    <input type="text" id="apellidos" name="apellidos" placeholder="Apellidos..." required
                        value="{{ old('apellidos') }}">
                </div>
                <div class="form-group">
                    <label for="curp">CURP*:</label>
                    <input type="text" id="curp" name="curp" placeholder="CURP..." required maxlength="18"
                        minlength="18" value="{{ old('curp') }}">
                </div>
                <div class="form-group">
                    <label for="fecha_nacimiento">Fecha de nacimiento*:</label>
                    <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required
                        value="{{ old('fecha_nacimiento') }}">
                </div>
                <div class="form-group">
                    <label for="sexo">Sexo:</label>
                    <select id="sexo" name="sexo">
                        <option value="">Seleccione una opción</option>
                        <option value="Hombre" {{ old('sexo') == 'Hombre' ? 'selected' : '' }}>Hombre</option>
                        <option value="Mujer" {{ old('sexo') == 'Mujer' ? 'selected' : '' }}>Mujer</option>
                        <option value="Otro" {{ old('sexo') == 'Otro' ? 'selected' : '' }}>Otro</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="direccion">Dirección:</label>
                    <input type="text" id="direccion" name="direccion" placeholder="Dirección..."
                        value="{{ old('direccion') }}">
                </div>
                <div class="form-group">
                    <label for="email_personal">Correo electrónico personal:</label>
                    <input type="email" id="email_personal" name="email_personal"
                        placeholder="Correo electrónico personal..." value="{{ old('email_personal') }}">
                </div>
                <div class="form-group">
                    <label for="telefono_personal">Teléfono personal:</label>
                    <input type="tel" id="telefono_personal" name="telefono_personal"
                        placeholder="Teléfono personal..." value="{{ old('telefono_personal') }}">
                </div>
                <div class="form-group">
                    <label for="nombre_emergencia">Nombre de emergencia:</label>
                    <input type="text" id="nombre_emergencia" name="nombre_emergencia"
                        placeholder="Nombre contacto..." value="{{ old('nombre_emergencia') }}">
                </div>
                <div class="form-group">
                    <label for="telefono_emergencia">Teléfono de emergencia:</label>
                    <input type="tel" id="telefono_emergencia" name="telefono_emergencia"
                        placeholder="Teléfono de emergencia..." value="{{ old('telefono_emergencia') }}">
                </div>
                <div class="form-group">
                    <label for="num_seguro">Número de seguro (11 Dígitos):</label>
                    <input type="text" id="num_seguro" name="num_seguro" placeholder="Número de seguro..."
                        value="{{ old('num_seguro') }}">
                </div>

                <h2>Información institucional:</h2>
                <div class="institution-carrera-group">
                    <div class="form-group">
                        <label for="institucion_select" required>Escuela o institución:</label>
                        <div class="institution-select-container">
                            <select id="institucion_select" name="institucion_id" class="form-control" required>
                                <option value="">Seleccione una institución</option>
                                @foreach ($instituciones as $institucion)
                                    <option value="{{ $institucion->id_institucion }}"
                                        {{ old('institucion_id') == $institucion->id_institucion ? 'selected' : '' }}>
                                        {{ $institucion->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="carrera_select" required>Carrera:</label>
                        <div class="carrera-select-container">
                            <select id="carrera_select" name="carrera_id" class="form-control" required>
                                <option value="">Primero seleccione una institución</option>
                            </select>
                            <i class="fas fa-spinner fa-spin loading-carreras" style="display: none;"></i>
                            <div class="no-carreras-message" style="display: none;">No se encontraron carreras para
                                esta institución</div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="email_institucional">Correo institucional:</label>
                    <input type="email" id="email_institucional" name="email_institucional"
                        placeholder="Correo institucional..." value="{{ old('email_institucional') }}">
                </div>
                <div class="form-group">
                    <label for="telefono_institucional">Teléfono institucional:</label>
                    <input type="tel" id="telefono_institucional" name="telefono_institucional"
                        placeholder="Teléfono institucional..." value="{{ old('telefono_institucional') }}">
                </div>
                <div class="form-group">
                    <label for="nivel_estudios">Nivel de estudios:</label>
                    <select id="nivel_estudios" name="nivel_estudios">
                        <option value="">Seleccione una opción</option>
                        <option value="Bachillerato" {{ old('nivel_estudios') == 'Bachillerato' ? 'selected' : '' }}>
                            Bachillerato</option>
                        <option value="Licenciatura" {{ old('nivel_estudios') == 'Licenciatura' ? 'selected' : '' }}>
                            Licenciatura</option>
                        <option value="Técnico" {{ old('nivel_estudios') == 'Técnico' ? 'selected' : '' }}>Técnico
                        </option>
                        <option value="Ingenieria" {{ old('nivel_estudios') == 'Ingenieria' ? 'selected' : '' }}>
                            Ingenieria</option>
                        <option value="Maestría" {{ old('nivel_estudios') == 'Maestría' ? 'selected' : '' }}>Maestría
                        </option>
                        <option value="Doctorado" {{ old('nivel_estudios') == 'Doctorado' ? 'selected' : '' }}>
                            Doctorado</option>
                    </select>
                </div>

                <h2>Información de prácticas:</h2>
                <div class="form-group">
                    <label for="estado_practicas">Estado:</label>
                    <select id="estado_practicas" name="estado_practicas">
                        <option value="ACTIVO" {{ old('estado_practicas') == 'ACTIVO' ? 'selected' : '' }}>ACTIVO
                        </option>
                        <option value="INACTIVO" {{ old('estado_practicas') == 'INACTIVO' ? 'selected' : '' }}>
                            INACTIVO</option>
                        <option value="FINALIZADO" {{ old('estado_practicas') == 'FINALIZADO' ? 'selected' : '' }}>
                            FINALIZADO</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="area_asignada">Área asignada* :</label>
                    <select id="area_asignada" name="area_asignada">
                        <option value="">Seleccione una opción</option>
                        <option value="Contraloria" {{ old('area_asignada') == 'Contraloria' ? 'selected' : '' }}>
                            Contraloria</option>
                        <option value="Ventas" {{ old('area_asignada') == 'Ventas' ? 'selected' : '' }}>Ventas
                        </option>
                        <option value="Sistemas" {{ old('area_asignada') == 'Sistemas' ? 'selected' : '' }}>Sistemas
                        </option>
                        <option value="AyB" {{ old('area_asignada') == 'AyB' ? 'selected' : '' }}>AyB</option>
                        <option value="Mantenimiento" {{ old('area_asignada') == 'Mantenimiento' ? 'selected' : '' }}>
                            Mantenimiento</option>
                        <option value="Recursos Humanos"
                            {{ old('area_asignada') == 'Recursos Humanos' ? 'selected' : '' }}>Recursos Humanos
                        </option>
                        <option value="Dirección" {{ old('area_asignada') == 'Dirección' ? 'selected' : '' }}>
                            Dirección</option>
                        <option value="Recepción" {{ old('area_asignada') == 'Recepción' ? 'selected' : '' }}>
                            Recepción</option>
                        <option value="Reservaciones" {{ old('area_asignada') == 'Reservaciones' ? 'selected' : '' }}>
                            Reservaciones</option>
                        <option value="Cocina" {{ old('area_asignada') == 'Cocina' ? 'selected' : '' }}>Cocina
                        </option>
                        <option value="Ama de llaves" {{ old('area_asignada') == 'Ama de llaves' ? 'selected' : '' }}>
                            Ama de llaves</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="fecha_inicio">Fecha de inicio*:</label>
                    <input type="date" id="fecha_inicio" name="fecha_inicio" required
                        value="{{ old('fecha_inicio') }}">
                </div>
                <div class="form-group">
                    <label for="fecha_final">Fecha de cierre:</label>
                    <input type="date" id="fecha_final" name="fecha_final" value="{{ old('fecha_final') }}">
                </div>
                <div class="form-group">
                    <label for="hora_entrada">Hora de entrada:</label>
                    <input type="time" id="hora_entrada" name="hora_entrada" value="{{ old('hora_entrada') }}">
                </div>
                <div class="form-group">
                    <label for="hora_salida">Hora de salida:</label>
                    <input type="time" id="hora_salida" name="hora_salida" value="{{ old('hora_salida') }}">
                </div>
                <div class="form-group">
                    <label for="horas_requeridas">Horas requeridas:</label>
                    <input type="number" id="horas_requeridas" name="horas_requeridas" min="0"
                        value="{{ old('horas_requeridas') }}">
                </div>
                <div class="form-group">
                    <label for="acceso_comedor">Habilitar acceso a comedor:</label>
                    <input type="checkbox" id="acceso_comedor" name="acceso_comedor" value="1"
                        {{ old('acceso_comedor') ? 'checked' : '' }}>
                </div>


                <h2>Información del Proyecto (Opcional)</h2>
                <div class="form-group">
                    <label for="incluir_proyecto">Asignar a un proyecto</label>
                    <input type="checkbox" id="incluir_proyecto" name="incluir_proyecto"
                        {{ old('incluir_proyecto') ? 'checked' : '' }}>
                </div>

                <div id="proyecto_fields" style="display: {{ old('incluir_proyecto') ? 'block' : 'none' }};">
                    <div class="form-group">
                        <label for="nombre_proyecto">Nombre del Proyecto:</label>
                        <input type="text" id="nombre_proyecto" name="nombre_proyecto" class="form-control"
                            value="{{ old('nombre_proyecto') }}">
                    </div>
                    <div class="form-group">
                        <label for="descripcion_proyecto">Descripción del Proyecto:</label>
                        <textarea id="descripcion_proyecto" name="descripcion_proyecto" class="form-control">{{ old('descripcion_proyecto') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="area_proyecto">Área asignada:</label>
                        <select id="area_proyecto" name="area_proyecto">
                            <option value="">Seleccione una opción</option>
                            <option value="Contraloria" {{ old('area_proyecto') == 'Contraloria' ? 'selected' : '' }}>
                                Contraloria</option>
                            <option value="Ventas" {{ old('area_proyecto') == 'Ventas' ? 'selected' : '' }}>Ventas
                            </option>
                            <option value="Sistemas" {{ old('area_proyecto') == 'Sistemas' ? 'selected' : '' }}>
                                Sistemas</option>
                            <option value="AyB" {{ old('area_proyecto') == 'AyB' ? 'selected' : '' }}>AyB</option>
                            <option value="Mantenimiento"
                                {{ old('area_proyecto') == 'Mantenimiento' ? 'selected' : '' }}>Mantenimiento</option>
                            <option value="Recursos Humanos"
                                {{ old('area_proyecto') == 'Recursos Humanos' ? 'selected' : '' }}>Recursos Humanos
                            </option>
                            <option value="Dirección" {{ old('area_proyecto') == 'Dirección' ? 'selected' : '' }}>
                                Dirección</option>
                            <option value="Recepción" {{ old('area_proyecto') == 'Recepción' ? 'selected' : '' }}>
                                Recepción</option>
                            <option value="Reservaciones"
                                {{ old('area_proyecto') == 'Reservaciones' ? 'selected' : '' }}>Reservaciones</option>
                            <option value="Cocina" {{ old('area_proyecto') == 'Cocina' ? 'selected' : '' }}>Cocina
                            </option>
                            <option value="Ama de llaves"
                                {{ old('area_proyecto') == 'Ama de llaves' ? 'selected' : '' }}>Ama de llaves</option>
                        </select>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="save-button">Guardar Practicante</button>
                    <button type="button" class="cancel-button">Cancelar</button>
                </div>

            </div>
            <script>
                window.instituciones = @json($instituciones->pluck('nombre', 'id_institucion'));
            </script>

        </form>
    </div>
    <script src="{{ asset('js/registrar_prac.js') }}"></script>
    <script src="{{ asset('js/getInfoCarrera.js') }}"></script>
    <script src="{{ asset('js/menu_modal.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Script para ocultar el anuncio de error después de 6 segundos
            const alert = document.querySelector('.alert-danger');
            if (alert) {
                setTimeout(() => {
                    alert.style.transition = 'opacity 1s ease-in-out';
                    alert.style.opacity = '0';
                    setTimeout(() => {
                        alert.remove();
                    }, 6000);
                }, 6000);
            }

            // Script para mantener la selección de institución y cargar carreras al recargar la página
            const institucionSelect = document.getElementById('institucion_select');
            const carreraSelect = document.getElementById('carrera_select');
            const oldInstitucionId = "{{ old('institucion_id') }}";
            const oldCarreraId = "{{ old('carrera_id') }}";

            // Script para la imagen
            document.getElementById('add-image-input').addEventListener('change', function(event) {
                const previewContainer = document.querySelector('.profile-image-container');
                const file = event.target.files[0];

                if (file && file.type.match('image.*')) {
                    if (file.size > 2 * 1024 * 1024) {
                        alert('La imagen no debe exceder los 2MB');
                        this.value = '';
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'profile-image';
                        img.alt = 'Vista previa';
                        previewContainer.innerHTML = '';
                        previewContainer.appendChild(img);

                        const tempImg = new Image();
                        tempImg.onload = function() {
                            if (Math.abs(this.width - this.height) > this.width * 0.1) {
                                alert(
                                    'La imagen no es perfectamente cuadrada. Se ajustará para mostrarse correctamente.');
                            }
                        };
                        tempImg.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Lógica para mostrar/ocultar campos de proyecto
            const incluirProyectoCheckbox = document.getElementById('incluir_proyecto');
            const proyectoFields = document.getElementById('proyecto_fields');

            incluirProyectoCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    proyectoFields.style.display = 'block';
                } else {
                    proyectoFields.style.display = 'none';
                    document.getElementById('nombre_proyecto').value = '';
                    document.getElementById('descripcion_proyecto').value = '';
                    document.getElementById('area_proyecto').value = '';
                }
            });
            // Mantener el estado del checkbox y los campos del proyecto al recargar
            if (incluirProyectoCheckbox.checked) {
                proyectoFields.style.display = 'block';
            }
        });
    </script>
    @if (session('success'))
    <script>
        localStorage.removeItem('institucion_id');
        localStorage.removeItem('carrera_id');
    </script>
    @endif
    <script>
        $('.cancel-button').on('click', function() {
            localStorage.removeItem('institucion_id');
            localStorage.removeItem('carrera_id');
            window.location.reload(); // O redirige a donde desees
        });
    </script>
</body>

</html>
