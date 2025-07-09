//Funcion para el tiempo
function updateDateTime() {
    const now = new Date();

    // Formatear hora
    const hours = now.getHours().toString().padStart(2, "0");
    const minutes = now.getMinutes().toString().padStart(2, "0");
    const ampm = hours >= 12 ? "pm" : "am";
    const displayHours = hours % 12 || 12;

    // Formatear fecha
    const day = now.getDate().toString().padStart(2, "0");
    const month = (now.getMonth() + 1).toString().padStart(2, "0");
    const year = now.getFullYear();

    // Actualizar elementos
    document.getElementById(
        "current-time"
    ).textContent = `${displayHours}:${minutes} ${ampm}`;
    document.getElementById(
        "current-date"
    ).textContent = `${day}/${month}/${year}`;
}
        function registrarEvento(tipo) {
            document.getElementById('tipo-evento').value = tipo;
            fetch(document.getElementById('bitacora-form').action, {
                    method: 'POST',
                    body: new FormData(document.getElementById('bitacora-form')),
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showModal('Éxito', data.message, true);
                        if (tipo === 'entrada') {
                            window.location.reload();
                        }
                    } else {
                        showModal('Error', data.message, false);
                    }
                })
                .catch(error => {
                    showModal('Error', 'Ocurrió un error inesperado', false);
                });
        }
// Mantener la función de actualización de fecha/hora
updateDateTime();
setInterval(updateDateTime, 60000);
