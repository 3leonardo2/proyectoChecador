<!DOCTYPE html>
<html lang="es">

<head>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta tus horas registradas</title>
    <link rel="stylesheet" href="{{ asset('css/consulta_horas.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        .date-filter {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
            align-items: center;
        }

        .date-filter input {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .date-filter button {
            padding: 8px 15px;
            background-color: #d3bc68;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .date-filter button:hover {
            background-color: #c0a855;
        }

        .no-event {
            color: #999;
            font-style: italic;
        }

        .date-cell {
            font-weight: bold;
            background-color: #f8f8f8;
        }
    </style>
</head>

<body>
    <div class="header">
        <a href="{{ route('bitacora.index') }}" class="back-button">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1>Consulta tus horas registradas</h1>
    </div>

    <div class="main-container">
        <div class="left-panel">
            <div class="input-section">
                <input type="text" id="codigoInput" placeholder="Ingresa tu código...">
                <button class="consultar-button" id="consultarBtn">CONSULTA TUS HORAS</button>
            </div>
            <div class="loading" id="loadingIndicator">
                <i class="fa-solid fa-spinner fa-spin"></i> Cargando datos...
            </div>
            <div class="error-message" id="errorMessage"></div>
            <div class="practicante-info-container" id="practicanteInfo" style="display: none;">
                <h3>Información del Practicante</h3>
                <p>Nombre: <span id="practicanteNombre"></span></p>
                <p>Institución: <span id="practicanteInstitucion"></span></p>
                <p>Carrera: <span id="practicanteCarrera"></span></p>
                <p>Periodo: <span id="practicantePeriodo"></span></p>

                <h3 class="mt-4">Horas</h3>
                <p>Horas Requeridas: <span id="horasTotales"></span></p>
                <p>Horas Registradas: <span id="horasRegistradas"></span></p>
                <p>Horas Faltantes: <span id="horasFaltantes"></span></p>
                <p>Porcentaje Completado: <span id="porcentajeCompletado"></span></p>
            </div>
        </div>
        <div class="right-panel">
            <div class="date-filter" id="dateFilter" style="display: none;">
                <input type="text" id="dateRangePicker" placeholder="Selecciona rango de fechas" readonly>
                <button id="filterBtn">Filtrar</button>
                <button id="resetFilterBtn">Mostrar todo</button>
            </div>
            <div class="table-container">
                <table id="registrosTable">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Entrada</th>
                            <th>Entrada Comedor</th>
                            <th>Salida Comedor</th>
                            <th>Salida</th>
                        </tr>
                    </thead>
                    <tbody id="registrosBody">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
    <script src="{{ asset('js/consultar_horas.js') }}">
        let allRegistros = [];
        let currentPracticanteCodigo = '';
        let datePicker = null;
    </script>
</body>

</html>
