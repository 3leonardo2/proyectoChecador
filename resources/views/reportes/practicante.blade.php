<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de {{ $practicante->nombre }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .header img {
            height: 80px;
        }

        .info-section {
            margin-bottom: 30px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .info-item {
            margin-bottom: 10px;
        }

        .info-item label {
            font-weight: bold;
            display: inline-block;
            width: 150px;
        }

        .progress-container {
            margin: 30px 0;
            text-align: center;
        }

        .progress-bar {
            height: 30px;
            background-color: #e0e0e0;
            border-radius: 15px;
            margin: 10px 0;
            position: relative;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background-color: #4CAF50;
            width: {{ min($porcentaje_completado, 100) }}%;
            border-radius: 15px;
            transition: width 0.3s ease;
        }

        .progress-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #333;
            font-weight: bold;
        }

        .revisiones-section {
            margin-top: 40px;
        }

        .revision-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .revision-table th,
        .revision-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .revision-table th {
            background-color: #f2f2f2;
        }

        .footer {
            margin-top: 50px;
            text-align: right;
            font-size: 0.8em;
            color: #666;
        }

        .img-container {
            border: 1px solid #ddd;
            padding: 5px;
            text-align: center;
            background-color: #f9f9f9;
            margin-bottom: 15px;
        }

        /* Ajuste responsive para la imagen */
        .img-container img {
            max-width: 100%;
            height: auto;
            max-height: 150px;
            /* Altura máxima */
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Reporte de Practicante</h1>
        <p>Generado el: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="info-section">
        <div style="display: flex; align-items: flex-start; gap: 30px;">
            <!-- Columna izquierda: Imagen (si existe) -->
            @if ($imagen)
                <div style="flex: 0 0 120px;"> <!-- Reduce el ancho fijo del contenedor -->
                    <div
                        style="border: 1px solid #ddd; padding: 5px; text-align: center; height: 150px; display: flex; flex-direction: column; justify-content: center;">
                        <img src="data:image/jpeg;base64,{{ $imagen }}" alt="Foto del practicante"
                            style="max-width: 100px; max-height: 120px; object-fit: contain; margin: 0 auto;">
                        <p style="margin-top: 5px; font-size: 10px; line-height: 1.2;">Foto del practicante</p>
                    </div>
                </div>
            @endif
            <div style="flex: 1;">
                <h2>Información General</h2>
                <div class="info-grid">
                    <div class="info-item">
                        <label>Nombre:</label>
                        <span>{{ $practicante->nombre }} {{ $practicante->apellidos }}</span>
                    </div>
                    <div class="info-item">
                        <label>Código:</label>
                        <span>{{ $practicante->codigo }}</span>
                    </div>
                    <div class="info-item">
                        <label>Institución:</label>
                        <span>{{ $practicante->institucion->nombre }}</span>
                    </div>
                    <div class="info-item">
                        <label>Carrera:</label>
                        <span>{{ $practicante->carrera->nombre_carr }}</span>
                    </div>
                    <div class="info-item">
                        <label>Área asignada:</label>
                        <span>{{ $practicante->area_asignada }}</span>
                    </div>
                    <div class="info-item">
                        <label>Estado:</label>
                        <span>{{ $practicante->estado_practicas }}</span>
                    </div>
                    <div class="info-item">
                        <label>Periodo:</label>
                        <span>
                            {{ \Carbon\Carbon::parse($practicante->fecha_inicio)->format('d/m/Y') }} -
                            {{ \Carbon\Carbon::parse($practicante->fecha_final)->format('d/m/Y') }}
                        </span>
                    </div>
                    <div class="info-item">
                        <label>Horario:</label>
                        <span>{{ $practicante->hora_entrada }} - {{ $practicante->hora_salida }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="progress-section">
        <h2>Progreso de Horas</h2>
        <div class="progress-container">
            <p>
                <strong>{{ $horas_completadas }}</strong> horas completadas de
                <strong>{{ $horas_requeridas }}</strong> requeridas
            </p>
            <div class="progress-bar">
                <div class="progress-fill"></div>
                <div class="progress-text">{{ round($porcentaje_completado, 2) }}% completado</div>
            </div>
        </div>
    </div>

    @if (isset($revisiones) && $revisiones->count() > 0)
        <div class="revisiones-section">
            <h2>Revisiones Registradas</h2>
            <table class="revision-table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Evaluador</th>
                        <th>Tipo</th>
                        <th>Descripción</th>
                        <th>Calificación</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($revisiones as $revision)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($revision->fecha_evaluacion)->format('d/m/Y') }}</td>
                            <td>{{ $revision->nombre_asesor }}</td>
                            <td>{{ $revision->nombre_revision }}</td>
                            <td>{{ $revision->descripcion_revision }}</td>
                            <td>{{ $revision->evaluacion_gral }}/5</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="footer">
        Sistema de Gestión de Practicantes - {{ date('Y') }}
    </div>
</body>

</html>
