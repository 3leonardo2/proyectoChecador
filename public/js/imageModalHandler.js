document.addEventListener("DOMContentLoaded", function () {
    const imageModal = document.getElementById("imageModal");
    if (!imageModal) return;

    // Elementos del modal
    const modalTitle = document.getElementById("modalTitle");
    const imageConfigForm = document.getElementById("imageConfigForm");
    if (!modalTitle || !imageConfigForm) return;
    const imageIdInput = document.getElementById("imageId");
    const imageUpload = document.getElementById("imageUploadModal");
    const imagePreview = document.getElementById("modalImagePreview");
    const imageTitle = document.getElementById("imageTitle");
    const imageDescription = document.getElementById("imageDescription");
    const startDate = document.getElementById("startDate");
    const endDate = document.getElementById("endDate");
    const displayDuration = document.getElementById("displayDuration");
    const closeButton = imageModal.querySelector(".close-button");
    const cancelButton = imageModal.querySelector(
        ".cancel-image-config-button"
    );

    const csrfToken = document.querySelector(
        'meta[name="csrf-token"]'
    )?.content;
    if (!csrfToken) {
        console.error("CSRF token not found");
        return;
    }

    // Funciones para abrir/cerrar el modal
    // Modifica la función openImageModal para el modo creación
    function openImageModal(imageData = null) {
        if (imageData) {
            // Modo edición (se mantiene igual)
            modalTitle.textContent = "Editar Imagen";
            imageIdInput.value = imageData.id;
            imageTitle.value = imageData.titulo || "";
            imageDescription.value = imageData.descripcion || "";
            startDate.value = formatDateTimeForInput(imageData.fecha_inicio);
            endDate.value = formatDateTimeForInput(imageData.fecha_fin);
            displayDuration.value = imageData.duracion || 5;

            if (imageData.ruta) {
                imagePreview.innerHTML = `<img src="${imageData.ruta}" alt="Previsualización" class="img-thumbnail">`;
            }
        } else {
            // Modo creación (con fechas locales)
            modalTitle.textContent = "Añadir Nueva Imagen";
            imageConfigForm.reset();
            imagePreview.innerHTML = "";

            // Obtener fecha y hora local actual
            const now = new Date();
            const localNow = new Date(
                now.getTime() - now.getTimezoneOffset() * 60000
            );

            // Fecha de fin (7 días después)
            const endDateValue = new Date(localNow);
            endDateValue.setDate(endDateValue.getDate() + 7);

            // Formatear sin conversión UTC
            startDate.value = localNow.toISOString().slice(0, 16);
            endDate.value = endDateValue.toISOString().slice(0, 16);
        }

        imageModal.classList.add("show");
    }

    function closeImageModal() {
        imageModal.classList.remove("show");
    }

    // Formatear fecha para input datetime-local
    function formatDateTimeForInput(dateTime) {
        if (!dateTime) return "";
        try {
            const date = new Date(dateTime);
            if (isNaN(date.getTime())) {
                console.error("Invalid date:", dateTime);
                return "";
            }

            // Formatear fecha y hora en formato local
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, "0");
            const day = String(date.getDate()).padStart(2, "0");
            const hours = String(date.getHours()).padStart(2, "0");
            const minutes = String(date.getMinutes()).padStart(2, "0");

            return `${year}-${month}-${day}T${hours}:${minutes}`;
        } catch (e) {
            console.error("Error formatting date:", e);
            return "";
        }
    }

    // Eventos para abrir el modal desde los botones
    document
        .querySelectorAll(".add-new-image-button, .edit-image-button")
        .forEach((button) => {
            button.addEventListener("click", function () {
                if (this.classList.contains("edit-image-button")) {
                    const imageItem = this.closest(".image-item");
                    const imageData = {
                        id: imageItem.dataset.imageId,
                        ruta: imageItem.querySelector("img").src,
                        titulo: imageItem.dataset.titulo || "",
                        descripcion: imageItem.dataset.descripcion || "",
                        fecha_inicio: imageItem.dataset.fechaInicio,
                        fecha_fin: imageItem.dataset.fechaFin,
                        duracion: imageItem.dataset.duracion || 5,
                    };
                    openImageModal(imageData);
                } else {
                    openImageModal();
                }
            });
        });

    // Eventos para cerrar el modal
    [closeButton, cancelButton].forEach((btn) => {
        btn.addEventListener("click", closeImageModal);
    });

    // Previsualización de imagen
    imageUpload.addEventListener("change", function () {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                imagePreview.innerHTML = `<img src="${e.target.result}" alt="Previsualización" class="img-thumbnail">`;
            };
            reader.readAsDataURL(this.files[0]);
        }
    });

    // Envío del formulario
    imageConfigForm.addEventListener("submit", function (e) {
        e.preventDefault();

        const formData = new FormData(this);
        const url = imageIdInput.value
            ? `/admin/imagenes-avisos/${imageIdInput.value}`
            : "/admin/imagenes-avisos";
        const method = imageIdInput.value ? "PUT" : "POST";

        fetch(url, {
            method: method,
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
                if (data.error) {
                    alert("Error: " + data.message);
                } else {
                    alert("Imagen guardada correctamente");
                    closeImageModal();
                    window.location.reload(); // Recargar para ver los cambios
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                alert("Ocurrió un error al guardar la imagen");
            });
    });

    // Eliminar imagen
    // En tu archivo imageModalHandler.js
document.querySelectorAll('.delete-image-button').forEach(button => {
    button.addEventListener('click', async function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        if (confirm('¿Estás seguro de que quieres eliminar esta imagen?')) {
            const imageItem = this.closest('.image-item');
            const imageId = imageItem.dataset.imageId;

            try {
                const response = await fetch(`/admin/imagenes-avisos/${imageId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Error en la respuesta del servidor');
                }

                if (data.success) {
                    imageItem.remove();
                    alert(data.message);
                    // Recargar después de 1 segundo para asegurar la sincronización
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    throw new Error(data.message || 'Error al eliminar la imagen');
                }
            } catch (error) {
                console.error('Error:', error);
                alert(`Error al eliminar la imagen: ${error.message}`);
            }
        }
    });
});
    document.querySelectorAll(".toggle-image-button").forEach((button) => {
        button.addEventListener("click", function () {
            const imageId = this.dataset.id;

            fetch(`/admin/imagenes-avisos/${imageId}/toggle`, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content,
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        window.location.reload(); // Recargar para ver cambios
                    } else {
                        alert("Error: " + data.message);
                    }
                })
                .catch((error) => {
                    console.error("Error:", error);
                    alert("Ocurrió un error al cambiar el estado");
                });
        });
    });
});
