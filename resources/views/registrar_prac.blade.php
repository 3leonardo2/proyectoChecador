<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Nuevo Practicante</title>
    <link rel="stylesheet" href="{{ asset('css/detailsprac.css') }}">
    <link rel="stylesheet" href="{{ asset('css/editprac.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="header">
        <a href="#" class="back-button">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1>Registrar Nuevo Practicante</h1>
    </div>

    <div class="main-container">
        <form action="{{ route('practicantes.store') }}" method="POST" class="practicante-info-wrapper" enctype="multipart/form-data"
        data-carrera-route="{{ route('practicantes.getByCarrera') }}">
            @csrf <div class="practicante-fixed-elements">
                <div class="practicante-profile-section">
                    <div class="profile-image-container"></div>
                    <input type="file" id="add-image-input" class="add-image-input" name="profile_image" accept="image/*" style="display: none;">
                    <label for="add-image-input" class="add-image-button">Añadir imagen...</label>
                </div>
            </div>

            <div class="practicante-scrollable-content">
                <h2>Datos Generales</h2>
                <div class="form-group">
                    <label for="nombre">Nombre*:</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Nombre..." required>
                </div>
                <div class="form-group">
                    <label for="apellidos">Apellidos*:</label>
                    <input type="text" id="apellidos" name="apellidos" placeholder="Apellidos..." required>
                </div>
                 <div class="form-group">
                    <label for="curp">CURP*:</label>
                    <input type="text" id="curp" name="curp" placeholder="CURP..." required maxlength="18" minlength="18">
                </div>
                <div class="form-group">
                    <label for="fecha_nacimiento">Fecha de nacimiento*:</label>
                    <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required>
                </div>
                <div class="form-group">
                    <label for="sexo">Sexo:</label>
                    <select id="sexo" name="sexo">
                        <option value="">Seleccione una opción</option>
                        <option value="Hombre">Hombre</option>
                        <option value="Mujer">Mujer</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="direccion">Dirección:</label>
                    <input type="text" id="direccion" name="direccion" placeholder="Dirección...">
                </div>
                <div class="form-group">
                    <label for="email_personal">Correo electrónico personal:</label>
                    <input type="email" id="email_personal" name="email_personal" placeholder="Correo electrónico personal...">
                </div>
                <div class="form-group">
                    <label for="telefono_personal">Teléfono personal:</label>
                    <input type="tel" id="telefono_personal" name="telefono_personal" placeholder="Teléfono personal...">
                </div>
                <div class="form-group">
                    <label for="nombre_emergencia">Nombre de emergencia:</label>
                    <input type="text" id="nombre_emergencia" name="nombre_emergencia" placeholder="Nombre contacto...">
                </div>
                <div class="form-group">
                    <label for="telefono_emergencia">Teléfono de emergencia:</label>
                    <input type="tel" id="telefono_emergencia" name="telefono_emergencia" placeholder="Teléfono de emergencia...">
                </div>
                <div class="form-group">
                    <label for="num_seguro">Número de seguro:</label>
                    <input type="text" id="num_seguro" name="num_seguro" placeholder="Número de seguro...">
                </div>

                <h2>Información institucional:</h2>
                <div class="form-group">
                    <label for="institucion_nombre">Escuela o institución*:</label>
                    <input type="text" id="institucion_nombre" name="institucion_nombre" placeholder="Escribe para buscar o añadir..." list="instituciones-list" required>
                    <datalist id="instituciones-list">
                        @foreach($instituciones as $institucion)
                            <option value="{{ $institucion->nombre }}">
                        @endforeach
                    </datalist>
                </div>
                <div class="form-group">
                    <label for="carrera_nombre">Carrera*:</label>
                    <input type="text" id="carrera_nombre" name="carrera_nombre" placeholder="Escribe para buscar o añadir..." list="carreras-list" required>
                    <datalist id="carreras-list">
                         @foreach($carreras as $carrera)
                            <option value="{{ $carrera->nombre_carr }}">
                        @endforeach
                    </datalist>
                </div>
                <div class="form-group">
                    <label for="email_institucional">Correo institucional:</label>
                    <input type="email" id="email_institucional" name="email_institucional" placeholder="Correo institucional...">
                </div>
                <div class="form-group">
                    <label for="telefono_institucional">Teléfono institucional:</label>
                    <input type="tel" id="telefono_institucional" name="telefono_institucional" placeholder="Teléfono institucional...">
                </div>
                <div class="form-group">
                    <label for="nivel_estudios">Nivel de estudios:</label>
                    <select id="nivel_estudios" name="nivel_estudios">
                        <option value="">Seleccione una opción</option>
                        <option value="Bachillerato">Bachillerato</option>
                        <option value="Licenciatura">Licenciatura</option>
                        <option value="Técnico">Técnico</option>
                        <option value="Ingenieria">Ingenieria</option>
                        <option value="Maestría">Maestría</option>
                        <option value="Doctorado">Doctorado</option>
                    </select>
                </div>

                <h2>Información de prácticas:</h2>
                <div class="form-group">
                    <label for="estado_practicas">Estado:</label>
                    <select id="estado_practicas" name="estado_practicas">
                        <option value="ACTIVO">ACTIVO</option>
                        <option value="INACTIVO">INACTIVO</option>
                        <option value="FINALIZADO">FINALIZADO</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="area_asignada">Área asignada:</label>
                <select id="area_asignada" name="area_asignada">
                        <option value="">Seleccione una opción</option>
                        <option value="Sistemas">Sistemas</option>
                        <option value="Mantenimiento">Mantenimiento</option>
                        <option value="Gastronomía">Gastronomía</option>
                        <option value="Panadería">Panadería</option>
                        <option value="Concierge">Concierge</option>
                        <option value="Ventas">Ventas</option>
                        <option value="LLamadas">Llamadas</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="fecha_inicio">Fecha de inicio*:</label>
                    <input type="date" id="fecha_inicio" name="fecha_inicio" required>
                </div>
                <div class="form-group">
                    <label for="fecha_final">Fecha de cierre:</label>
                    <input type="date" id="fecha_final" name="fecha_final">
                </div>
                <div class="form-group">
                    <label for="hora_entrada">Hora de entrada:</label>
                    <input type="time" id="hora_entrada" name="hora_entrada">
                </div>
                <div class="form-group">
                    <label for="hora_salida">Hora de salida:</label>
                    <input type="time" id="hora_salida" name="hora_salida">
                </div>
                <div class="form-group">
                    <label for="horas_requeridas">Horas requeridas:</label>
                    <input type="number" id="horas_requeridas" name="horas_requeridas" min="0">
                </div>

                <div class="form-actions">
                    <button type="submit" class="save-button">Guardar Practicante</button>
                    <button type="button" class="cancel-button">Cancelar</button>
                </div>
            </div>
            <script src="js/getInfoCarrera.js"></script>
        </form>
    </div>
</body>
</html>