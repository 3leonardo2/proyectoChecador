<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Nuevo Practicante</title>
    <link rel="stylesheet" href="css/detailsprac.css">
    <link rel="stylesheet" href="css/editprac.css">
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
        <form action="#" method="POST" class="practicante-info-wrapper">
            <div class="practicante-fixed-elements">
                <div class="practicante-profile-section">
                    <div class="profile-image-container">

                    </div>
                    <input type="file" id="add-image-input" class="add-image-input" name="profile_image" accept="image/*" style="display: none;">
                    <label for="add-image-input" class="add-image-button">Añadir imagen...</label>
                    <div class="practicante-codigo">Código: LAE</div>
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
                    <label for="numero_seguro">Número de seguro:</label>
                    <input type="text" id="numero_seguro" name="numero_seguro" placeholder="Número de seguro...">
                </div>
                <div class="form-group">
                    <label for="numero_seguro">CURP:</label>
                    <input type="text" id="curp" name="curp" placeholder="CURP...">
                </div>

                <h2>Información institucional:</h2>
                <div class="form-group">
                    <label for="escuela">Escuela o institución:</label>
                    <input type="text" id="escuela" name="escuela" placeholder="Escuela o institución...">
                </div>
                <div class="form-group">
                    <label for="carrera">Carrera:</label>
                    <input type="text" id="carrera" name="carrera" placeholder="Carrera...">
                </div>
                <div class="form-group">
                    <label for="correo_institucional">Correo institucional:</label>
                    <input type="email" id="correo_institucional" name="correo_institucional" placeholder="Correo institucional...">
                </div>
                <div class="form-group">
                    <label for="telefono_institucional">Teléfono institucional:</label>
                    <input type="tel" id="telefono_institucional" name="telefono_institucional" placeholder="Teléfono institucional...">
                </div>
                <div class="form-group">
                    <label for="direccion">Dirección:</label>
                    <input type="text" id="direccion" name="direccion" placeholder="Dirección...">
                </div>
                <div class="form-group">
                    <label for="nivel_estudios">Nivel de estudios:</label>
                    <select id="nivel_estudios" name="nivel_estudios">
                        <option value="">Seleccione una opción</option>
                        <option value="Bachillerato">Bachillerato</option>
                        <option value="Licenciatura">Licenciatura</option>
                        <option value="Maestría">Maestría</option>
                        <option value="Doctorado">Doctorado</option>
                    </select>
                </div>

                <h2>Información de prácticas:</h2>
                <div class="form-group">
                    <label for="estado_practicas">Estado:</label>
                    <select id="estado_practicas" name="estado_practicas">
                        <option value="Seleccione una opción">Selecciona una opción</option>
                        <option value="ACTIVO">ACTIVO</option>
                        <option value="INACTIVO">INACTIVO</option>
                        <option value="FINALIZADO">FINALIZADO</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="area_asignada">Área asignada:</label>
                    <input type="text" id="area_asignada" name="area_asignada" placeholder="Área asignada...">
                </div>
                <div class="form-group">
                    <label for="fecha_inicio_practicas">Fecha de inicio:</label>
                    <input type="date" id="fecha_inicio_practicas" name="fecha_inicio_practicas">
                </div>
                <div class="form-group">
                    <label for="fecha_cierre_practicas">Fecha de cierre:</label>
                    <input type="date" id="fecha_cierre_practicas" name="fecha_cierre_practicas">
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
                <div class="form-group">
                    <label for="horas_registradas">Horas registradas:</label>
                    <input type="number" id="horas_registradas" name="horas_registradas" min="0">
                </div>

                <div class="form-actions">
                    <button type="submit" class="save-button">Guardar cambios</button>
                    <button type="button" class="cancel-button">Cancelar</button>
                </div>
            </div>
        </form>
    </div>
</body>
</html>