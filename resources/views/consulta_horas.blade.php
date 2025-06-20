<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta tus horas registradas</title>
    <link rel="stylesheet" href="{{ asset('css/consulta_horas.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <div class="header">
        <a href="#" class="back-button">
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
            <div class="table-container">
                <table id="registrosTable">
                    <thead>
                        <tr>
                            <th>Fecha <i class="fa-solid fa-caret-down sort-icon"></i></th>
                            <th>Tipo</th>
                            <th>Hora</th>
                        </tr>
                    </thead>
                    <tbody id="registrosBody">
                        <!-- Datos se cargarán dinámicamente -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('consultarBtn').addEventListener('click', function() {
            const codigo = document.getElementById('codigoInput').value.trim();

            if (!codigo) {
                showError('Por favor ingresa tu código');
                return;
            }

            fetchPracticanteData(codigo);
        });
        document.addEventListener('DOMContentLoaded', function() {
            // Ocultar el indicador de carga al iniciar
            document.getElementById('loadingIndicator').style.display = 'none';

            document.getElementById('consultarBtn').addEventListener('click', function() {
                const codigo = document.getElementById('codigoInput').value.trim();

                if (!codigo) {
                    showError('Por favor ingresa tu código');
                    return;
                }

                // Mostrar solo cuando se inicia la búsqueda
                document.getElementById('loadingIndicator').style.display = 'block';
                document.getElementById('practicanteInfo').style.display = 'none';
                document.getElementById('errorMessage').style.display = 'none';

                fetchPracticanteData(codigo);
            });
        });

        function fetchPracticanteData(codigo) {
            document.getElementById('loadingIndicator').style.display = 'block';
            document.getElementById('practicanteInfo').style.display = 'none';
            document.getElementById('errorMessage').style.display = 'none';

            fetch(`/api/practicante/${codigo}`)
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => {
                            throw new Error(err.message || 'Error del servidor');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (!data.success) {
                        throw new Error(data.error || 'Datos incorrectos recibidos');
                    }

                    // Debug: muestra los datos en consola
                    console.log('Datos recibidos:', data);

                    // Verifica que los datos del practicante existen
                    if (!data.practicante) {
                        throw new Error('No se encontraron datos del practicante');
                    }

                    displayPracticanteData(data.practicante);
                    displayRegistros(data.registros);
                })
                .catch(error => {
                    console.error('Error al obtener datos:', error);
                    showError(error.message || 'Error al cargar los datos');
                })
                .finally(() => {
                    document.getElementById('loadingIndicator').style.display = 'none';
                });
        }

        function displayPracticanteData(practicante) {
            console.log('Datos del practicante recibidos:', practicante);

            // Actualiza estos campos según la respuesta de la API
            document.getElementById('practicanteNombre').textContent = practicante.nombre_completo || 'No disponible';
            document.getElementById('practicanteInstitucion').textContent = practicante.institucion || 'No especificada';
            document.getElementById('practicanteCarrera').textContent = practicante.carrera || 'No especificada';
            document.getElementById('practicantePeriodo').textContent =
                `${formatDate(practicante.fecha_inicio)} al ${formatDate(practicante.fecha_final)}`;

            // Cambiado de horas_totales a horas_requeridas
            document.getElementById('horasTotales').textContent = practicante.horas_requeridas || 0;
            document.getElementById('horasRegistradas').textContent = practicante.horas_registradas || 0;

            const horasFaltantes = (practicante.horas_requeridas || 0) - (practicante.horas_registradas || 0);
            document.getElementById('horasFaltantes').textContent = horasFaltantes > 0 ? horasFaltantes : 0;

            const porcentaje = practicante.horas_requeridas > 0 ?
                Math.round(((practicante.horas_registradas || 0) / practicante.horas_requeridas) * 100) : 0;
            document.getElementById('porcentajeCompletado').textContent = `${porcentaje}%`;

            // Muestra el contenedor
            document.getElementById('practicanteInfo').style.display = 'block';
        }

        function formatDate(dateString) {
            if (!dateString) return 'No definido';
            const date = new Date(dateString);
            return date.toLocaleDateString('es-MX', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            });
        }

        function displayRegistros(registros) {
            const tbody = document.getElementById('registrosBody');
            tbody.innerHTML = '';

            if (registros.length === 0) {
                const tr = document.createElement('tr');
                tr.innerHTML = '<td colspan="3" style="text-align: center;">No hay registros encontrados</td>';
                tbody.appendChild(tr);
                return;
            }

            registros.forEach((registro, index) => {
                const tr = document.createElement('tr');
                if (index % 2 === 0) {
                    tr.classList.add('highlighted-row');
                }

                // Formatear fecha
                const fecha = new Date(registro.fecha);
                const fechaFormateada = fecha.toLocaleDateString('es-MX');

                // Formatear hora
                const hora = new Date(`1970-01-01T${registro.hora}`);
                const horaFormateada = hora.toLocaleTimeString('es-MX', {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true
                }).replace(/^0/, '');

                tr.innerHTML = `
                    <td>${fechaFormateada}</td>
                    <td>${formatTipoEvento(registro.tipo)}</td>
                    <td>${horaFormateada}</td>
                `;

                tbody.appendChild(tr);
            });
        }

        function formatTipoEvento(tipo) {
            const tipos = {
                'entrada': 'Entrada',
                'salida': 'Salida',
                'entrada_comedor': 'Entrada Comedor',
                'salida_comedor': 'Salida Comedor'
            };
            return tipos[tipo] || tipo;
        }

        function showError(message) {
            const errorElement = document.getElementById('errorMessage');
            errorElement.textContent = message;
            errorElement.style.display = 'block';
            document.getElementById('practicanteInfo').style.display = 'none';
        }
    </script>
</body>

</html>
