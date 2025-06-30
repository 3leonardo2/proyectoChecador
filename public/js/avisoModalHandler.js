document.addEventListener("DOMContentLoaded", function () {
    const avisoModal = document.getElementById("avisoModal");
    const addAvisoButton = document.querySelector(".add-aviso-button");
    const editAvisoButtons = document.querySelectorAll(".edit-aviso");

    if (!avisoModal || !addAvisoButton) return;

    const closeButton = avisoModal.querySelector(".close-button");
    const cancelButton = avisoModal.querySelector(".cancel-aviso-button");
    const avisoForm = document.getElementById("avisoForm");

    function openAvisoModal(avisoData = null) {
        avisoForm.reset(); // Resetea el formulario al inicio
        if (avisoData) {
            document.getElementById("avisoModalTitle").textContent =
                "Editar Aviso";
            document.getElementById("avisoId").value = avisoData.id;
            document.getElementById("contenido").value = avisoData.contenido;
            document.getElementById("fecha_inicio").value =
                avisoData.fechaInicio;
            document.getElementById("fecha_fin").value = avisoData.fechaFin;
            document.getElementById("avisoMethod").value = "PUT";
        } else {
            document.getElementById("avisoModalTitle").textContent =
                "Añadir Nuevo Aviso";
            document.getElementById("avisoId").value = "";
            document.getElementById("avisoMethod").value = "POST";

            const now = new Date();
            const endDate = new Date();
            endDate.setDate(now.getDate() + 7); // +7 días

            // Función para formatear la fecha en formato datetime-local sin conversión UTC
            function formatLocalDateTime(date) {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, "0");
                const day = String(date.getDate()).padStart(2, "0");
                const hours = String(date.getHours()).padStart(2, "0");
                const minutes = String(date.getMinutes()).padStart(2, "0");

                return `${year}-${month}-${day}T${hours}:${minutes}`;
            }

            document.getElementById("fecha_inicio").value =
                formatLocalDateTime(now);
            document.getElementById("fecha_fin").value =
                formatLocalDateTime(endDate);
        }
        avisoModal.style.display = "block";
    }

    function closeAvisoModal() {
        avisoModal.style.display = "none";
    }

    addAvisoButton.addEventListener("click", () => openAvisoModal());

    editAvisoButtons.forEach((button) => {
        button.addEventListener("click", function () {
            const avisoItem = this.closest(".aviso-item");
            const avisoData = {
                id: avisoItem.dataset.avisoId,
                contenido: avisoItem
                    .querySelector("p")
                    .textContent.replace(/^-\s*/, "")
                    .trim(),
                fechaInicio: avisoItem.dataset.fechaInicio,
                fechaFin: avisoItem.dataset.fechaFin,
            };
            openAvisoModal(avisoData);
        });
    });

    if (closeButton) closeButton.addEventListener("click", closeAvisoModal);
    if (cancelButton) cancelButton.addEventListener("click", closeAvisoModal);

    window.addEventListener("click", function (event) {
        if (event.target === avisoModal) {
            closeAvisoModal();
        }
    });

    // Listener para el envío del formulario
    avisoForm.addEventListener("submit", function (e) {
        e.preventDefault();

        const formData = new FormData(this);
        const avisoId = document.getElementById("avisoId").value;
        const method = document.getElementById("avisoMethod").value;

        // La URL es más simple de construir ahora
        const url = avisoId ? `/avisos/${avisoId}` : "/avisos";

        fetch(url, {
            method: "POST", // Siempre usamos POST, Laravel lo interpreta con el campo _method
            body: formData,
            headers: {
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]'
                ).content,
                Accept: "application/json",
            },
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    alert(data.message);
                    // No es necesario cerrar el modal aquí, la página se recargará
                    window.location.reload();
                } else {
                    // Manejo de errores de validación (opcional pero recomendado)
                    let errorMsg = data.message;
                    if (data.errors) {
                        errorMsg +=
                            "\n" +
                            Object.values(data.errors)
                                .map((e) => e.join("\n"))
                                .join("\n");
                    }
                    alert("Error: " + errorMsg);
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                alert("Ocurrió un error inesperado al guardar el aviso.");
            });
    });

    // Listener para eliminar avisos
    document.querySelectorAll(".delete-aviso").forEach((button) => {
        button.addEventListener("click", function () {
            if (confirm("¿Estás seguro de que quieres eliminar este aviso?")) {
                const avisoItem = this.closest(".aviso-item");
                const avisoId = avisoItem.dataset.avisoId;

                fetch(`/avisos/${avisoId}`, {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector(
                            'meta[name="csrf-token"]'
                        ).content,
                        Accept: "application/json",
                    },
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            avisoItem.remove();
                            alert(data.message);
                        } else {
                            alert("Error: " + data.message);
                        }
                    })
                    .catch((error) => {
                        console.error("Error:", error);
                        alert("Ocurrió un error al eliminar el aviso.");
                    });
            }
        });
    });
});
