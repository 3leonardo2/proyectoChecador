let allRegistros = [];
let currentPracticanteCodigo = "";
let datePicker = null;

document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("loadingIndicator").style.display = "none";

    // Inicializar el datepicker
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
                    throw new Error(err.error || "Error al obtener datos del practicante");
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

            displayPracticanteData(data.practicante);

            // Guardar todos los registros para filtrado
            allRegistros = data.registros;
            displayRegistros(allRegistros);

            // Mostrar el filtro de fechas
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

function filterRegistros() {
    const selectedDates = datePicker.selectedDates;

    if (selectedDates.length === 0) {
        displayRegistros(allRegistros);
        return;
    }

    const startDate = selectedDates[0];
    let endDate = startDate;

    if (selectedDates.length > 1) {
        endDate = selectedDates[1];
    }

    // Ajustar las fechas para incluir todo el día
    startDate.setHours(0, 0, 0, 0);
    endDate.setHours(23, 59, 59, 999);

    const filtered = allRegistros.filter((registro) => {
        const registroDate = new Date(registro.fecha);
        return registroDate >= startDate && registroDate <= endDate;
    });

    displayRegistros(filtered);
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
    const date = new Date(dateString);
    if (isNaN(date.getTime())) {
        // Si el formato no es válido, intentar parsearlo manualmente
        const parts = dateString.split("-");
        if (parts.length === 3) {
            return `${parts[2]}/${parts[1]}/${parts[0]}`;
        }
        return dateString; // Devolver original si no se puede parsear
    }
    return date.toLocaleDateString("es-MX", {
        day: "2-digit",
        month: "2-digit",
        year: "numeric",
    });
}

function formatTime(timeString) {
    if (!timeString) return "";
    const [hours, minutes] = timeString.split(":");
    const date = new Date();
    date.setHours(hours);
    date.setMinutes(minutes);
    return date
        .toLocaleTimeString("es-MX", {
            hour: "2-digit",
            minute: "2-digit",
            hour12: true,
        })
        .replace(/^0/, "");
}

function displayRegistros(registros) {
    const tbody = document.getElementById("registrosBody");
    tbody.innerHTML = "";

    if (registros.length === 0) {
        const tr = document.createElement("tr");
        tr.innerHTML =
            '<td colspan="5" style="text-align: center;">No hay registros encontrados</td>';
        tbody.appendChild(tr);
        return;
    }

    // Agrupar registros por fecha
    const registrosPorDia = {};
    registros.forEach((registro) => {
        if (!registrosPorDia[registro.fecha]) {
            registrosPorDia[registro.fecha] = [];
        }
        registrosPorDia[registro.fecha].push(registro);
    });

    // Ordenar fechas de más reciente a más antigua
    const fechasOrdenadas = Object.keys(registrosPorDia).sort(
        (a, b) => new Date(b) - new Date(a)
    );

    // Procesar cada día
    fechasOrdenadas.forEach((fecha) => {
        const registrosDia = registrosPorDia[fecha];

        // Ordenar registros por hora
        registrosDia.sort((a, b) => a.hora.localeCompare(b.hora));

        // Procesar secuencias de eventos
        let i = 0;
        while (i < registrosDia.length) {
            const tr = document.createElement("tr");

            // Formatear fecha
            const fechaFormateada = formatDate(fecha);

            // Inicializar valores para esta fila
            let entrada = "";
            let entradaComedor = "";
            let salidaComedor = "";
            let salida = "";

            // Procesar secuencia completa (entrada -> entrada_comedor -> salida_comedor -> salida)
            if (i < registrosDia.length && registrosDia[i].tipo === "entrada") {
                entrada = formatTime(registrosDia[i].hora);
                i++;

                if (
                    i < registrosDia.length &&
                    registrosDia[i].tipo === "entrada_comedor"
                ) {
                    entradaComedor = formatTime(registrosDia[i].hora);
                    i++;

                    if (
                        i < registrosDia.length &&
                        registrosDia[i].tipo === "salida_comedor"
                    ) {
                        salidaComedor = formatTime(registrosDia[i].hora);
                        i++;
                    }
                }

                if (
                    i < registrosDia.length &&
                    registrosDia[i].tipo === "salida"
                ) {
                    salida = formatTime(registrosDia[i].hora);
                    i++;
                }
            } else {
                // Si no sigue el patrón esperado, mostrar lo que haya
                const registro = registrosDia[i];
                switch (registro.tipo) {
                    case "entrada":
                        entrada = formatTime(registro.hora);
                        break;
                    case "entrada_comedor":
                        entradaComedor = formatTime(registro.hora);
                        break;
                    case "salida_comedor":
                        salidaComedor = formatTime(registro.hora);
                        break;
                    case "salida":
                        salida = formatTime(registro.hora);
                        break;
                }
                i++;
            }

            // Crear fila de la tabla
            tr.innerHTML = `
                        <td class="date-cell">${fechaFormateada}</td>
                        <td>${entrada || "<span >-</span>"}</td>
                        <td>${entradaComedor || "<span>-</span>"}</td>
                        <td>${salidaComedor || "<span >-</span>"}</td>
                        <td>${salida || "<span >-</span>"}</td>
                    `;

            tbody.appendChild(tr);
        }
    });
}

function showError(message) {
    const errorElement = document.getElementById("errorMessage");
    errorElement.textContent = message;
    errorElement.style.display = "block";
    document.getElementById("practicanteInfo").style.display = "none";
    document.getElementById("dateFilter").style.display = "none";
}
