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

// Actualizar inmediatamente y cada minuto
updateDateTime();
setInterval(updateDateTime, 60000);

function registrarEvento(tipo) {
    document.getElementById("tipo-evento").value = tipo;
    fetch(document.getElementById("bitacora-form").action, {
        method: "POST",
        body: new FormData(document.getElementById("bitacora-form")),
        headers: {
            "X-CSRF-TOKEN": document.querySelector('input[name="_token"]')
                .value,
            Accept: "application/json",
        },
    })
        .then((response) => {
            if (!response.ok) {
                return response.json().then((err) => {
                    throw err;
                });
            }
            return response.json();
        })
        .then((data) => {
            showModal(
                data.success ? "Éxito" : "Error",
                data.message,
                data.success
            );
            if (tipo === "entrada" && data.success) {
                window.location.reload(); // Recarga solo para entrada (para mostrar mensaje de bienvenida)
            }
        })
        .catch((error) => {
            showModal("Error", error.message || "Error inesperado", false);
        });
}

function showModal(title, message, isSuccess) {
    const modal = document.getElementById("alertModal");
    const icon = document.getElementById("alertModalIcon");
    const msg = document.getElementById("alertModalMessage");

    // Configura el modal según éxito/error
    modal.className = `alert-modal ${isSuccess ? "success" : "error"}`;
    icon.innerHTML = isSuccess
        ? '<i class="fas fa-check-circle"></i>'
        : '<i class="fas fa-exclamation-circle"></i>';
    msg.textContent = message;

    // Muestra el modal con animación
    modal.style.display = "flex";

    // Cierra automáticamente después de 5 segundos
    setTimeout(() => {
        modal.style.animation = "fadeOut 0.5s ease-out";
        setTimeout(() => {
            modal.style.display = "none";
        }, 500);
    }, 5000);
}
