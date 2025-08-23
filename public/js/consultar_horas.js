let allRegistros = [];

let currentPracticanteCodigo = "";

let datePicker = null;

document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("loadingIndicator").style.display = "none"; // Inicializar el datepicker

    datePicker = flatpickr("#dateRangePicker", {
        mode: "range",

        locale: "es",

        dateFormat: "Y-m-d",

        maxDate: "today",
    });

    document

        .getElementById("consultarBtn")

        .addEventListener("click", function () {
            const codigo = document.getElementById("codigoInput").value.trim();

            if (!codigo) {
                showError("Por favor ingresa tu código");

                return;
            }

            document.getElementById("loadingIndicator").style.display = "block";

            document.getElementById("practicanteInfo").style.display = "none";

            document.getElementById("errorMessage").style.display = "none";

            fetchPracticanteData(codigo);
        });

    document.getElementById("filterBtn").addEventListener("click", function () {
        filterRegistros();
    });

    document

        .getElementById("resetFilterBtn")

        .addEventListener("click", function () {
            datePicker.clear();

            displayRegistros(allRegistros);
        });
});

function fetchPracticanteData(codigo) {
    currentPracticanteCodigo = codigo;

    document.getElementById("loadingIndicator").style.display = "block";

    document.getElementById("practicanteInfo").style.display = "none";

    document.getElementById("errorMessage").style.display = "none";

    fetch(`/api/practicante/${codigo}`)
        .then((response) => {
            if (!response.ok) {
                return response.json().then((err) => {
                    throw new Error(
                        err.error || "Error al obtener datos del practicante"
                    );
                });
            }

            return response.json();
        })

        .then((data) => {
            if (!data.success) {
                throw new Error(data.error || "Datos incorrectos recibidos");
            }

            console.log("Datos recibidos:", data);

            if (!data.practicante) {
                throw new Error("No se encontraron datos del practicante");
            }

            displayPracticanteData(data.practicante); // Guardar todos los registros para filtrado

            allRegistros = data.registros;

            displayRegistros(allRegistros); // Mostrar el filtro de fechas

            document.getElementById("dateFilter").style.display = "flex";
        })

        .catch((error) => {
            console.error("Error al obtener datos:", error);

            showError(error.message || "Error al cargar los datos");

            document.getElementById("dateFilter").style.display = "none";
        })

        .finally(() => {
            document.getElementById("loadingIndicator").style.display = "none";
        });
}

function adjustToMexicoTimezone(date) {
    const mexicoOffset = -6 * 60; // UTC-6 en minutos

    const localOffset = date.getTimezoneOffset();

    const diff = mexicoOffset - localOffset;

    return new Date(date.getTime() + diff * 60000);
}

function displayPracticanteData(practicante) {
    document.getElementById("practicanteNombre").textContent =
        practicante.nombre_completo || "No disponible";

    document.getElementById("practicanteInstitucion").textContent =
        practicante.institucion || "No especificada";

    document.getElementById("practicanteCarrera").textContent =
        practicante.carrera || "No especificada";

    document.getElementById("practicantePeriodo").textContent = `${formatDate(
        practicante.fecha_inicio
    )} al ${formatDate(practicante.fecha_final)}`;

    document.getElementById("horasTotales").textContent =
        practicante.horas_requeridas || 0;

    document.getElementById("horasRegistradas").textContent =
        practicante.horas_registradas || 0;

    const horasFaltantes =
        (practicante.horas_requeridas || 0) -
        (practicante.horas_registradas || 0);

    document.getElementById("horasFaltantes").textContent =
        horasFaltantes > 0 ? horasFaltantes : 0;

    const porcentaje =
        practicante.horas_requeridas > 0
            ? Math.round(
                  ((practicante.horas_registradas || 0) /
                      practicante.horas_requeridas) *
                      100
              )
            : 0;

    document.getElementById(
        "porcentajeCompletado"
    ).textContent = `${porcentaje}%`;

    document.getElementById("practicanteInfo").style.display = "block";
}

function formatDate(dateString) {
    if (!dateString) return "No definido";

    const [year, month, day] = dateString.split("-");

    if (year && month && day) {
        return `${day}/${month}/${year}`;
    }

    return dateString; // Devolver original si no se puede parsear
}

function formatTime(timeString) {
    if (!timeString) return ""; // Extraer horas y minutos directamente del string HH:MM:SS

    const [hours, minutes] = timeString.split(":"); // Convertir a formato 12 horas

    const hour12 = hours % 12 || 12;

    const ampm = hours >= 12 ? "p.m." : "a.m.";

    return `${hour12}:${minutes} ${ampm}`;
}

function filterRegistros() {
    const selectedDates = datePicker.selectedDates;

    if (selectedDates.length === 0) {
        displayRegistros(allRegistros);

        return;
    } // Para las fechas seleccionadas por el usuario (ya en hora local)

    const startDate = new Date(selectedDates[0]);

    const endDate =
        selectedDates.length > 1
            ? new Date(selectedDates[1])
            : new Date(startDate); // Ajustar a inicio y fin del día

    startDate.setHours(0, 0, 0, 0);

    endDate.setHours(23, 59, 59, 999);

    const filtered = allRegistros.filter((registro) => {
        const registroDate = new Date(registro.fecha + "T00:00:00-06:00");

        return registroDate >= startDate && registroDate <= endDate;
    });

    displayRegistros(filtered);
}

function displayRegistros(registros) {
    const tbody = document.getElementById("registrosBody");
    tbody.innerHTML = "";

    if (registros.length === 0) {
        const tr = document.createElement("tr");
        tr.innerHTML = '<td colspan="5" style="text-align: center;">No hay registros encontrados</td>';
        tbody.appendChild(tr);
        return;
    }

    // Paso 1: Agrupar registros por fecha
    const registrosPorDia = {};
    registros.forEach((registro) => {
        const fecha = new Date(registro.fecha).toISOString().split('T')[0];
        if (!registrosPorDia[fecha]) {
            registrosPorDia[fecha] = [];
        }
        registrosPorDia[fecha].push(registro);
    });

    // Paso 2: Obtener fechas ordenadas de más reciente a más antigua
    const fechasOrdenadas = Object.keys(registrosPorDia).sort((a, b) => new Date(b) - new Date(a));

    // Paso 3: Iterar sobre cada día y crear una fila consolidada
    fechasOrdenadas.forEach((fecha) => {
        const eventosDia = registrosPorDia[fecha];
        const tr = document.createElement("tr");

        // Inicializar un objeto para guardar las horas del día
        const horasDia = {
            entrada: '-',
            entrada_comedor: '-',
            salida_comedor: '-',
            salida: '-',
        };

        // Rellenar las horas con los datos reales
        eventosDia.forEach(evento => {
            const tipoEvento = evento.tipo;
            if (horasDia.hasOwnProperty(tipoEvento)) {
                horasDia[tipoEvento] = formatTime(evento.hora);
            }
        });

        // Formatear la fecha para la visualización
        const fechaFormateada = new Date(fecha + 'T00:00:00-06:00').toLocaleDateString('es-ES', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
        });

        // Construir la fila HTML
        tr.innerHTML = `
            <td class="date-cell">${fechaFormateada}</td>
            <td>${horasDia.entrada}</td>
            <td>${horasDia.entrada_comedor}</td>
            <td>${horasDia.salida_comedor}</td>
            <td>${horasDia.salida}</td>
        `;

        tbody.appendChild(tr);
    });
}
function showError(message) {
    const errorElement = document.getElementById("errorMessage");

    errorElement.textContent = message;

    errorElement.style.display = "block";

    document.getElementById("practicanteInfo").style.display = "none";

    document.getElementById("dateFilter").style.display = "none";
}
