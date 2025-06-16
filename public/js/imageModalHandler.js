// public/js/imageModalHandler.js

document.addEventListener('DOMContentLoaded', function() {
    const imageModal = document.getElementById('imageModal');
    if (!imageModal) { // Asegura que el script solo se ejecute si el modal existe
        return;
    }

    const closeButton = imageModal.querySelector('.close-button');
    const addNewImageButton = document.querySelector('.add-new-image-button');
    const editImageButtons = document.querySelectorAll('.edit-image-button'); // Estos deben ser seleccionados en el HTML
    const saveImageConfigButton = imageModal.querySelector('.save-image-config-button');
    const cancelImageConfigButton = imageModal.querySelector('.cancel-image-config-button');
    const imageUploadModal = document.getElementById('imageUploadModal');
    const modalImagePreview = document.getElementById('modalImagePreview');
    const startDateInput = document.getElementById('startDate');
    const endDateInput = document.getElementById('endDate');
    const displayDurationInput = document.getElementById('displayDuration');

    let currentImageId = null; // Para saber si estamos editando o añadiendo

    // Función para abrir el modal
    function openImageModal(imageData = null) {
        if (imageData) { // Modo edición
            currentImageId = imageData.id;
            imageModal.querySelector('h3').textContent = 'Editar Imagen';
            if (imageData.url) {
                modalImagePreview.innerHTML = `<img src="${imageData.url}" alt="Previsualización">`;
            } else {
                modalImagePreview.innerHTML = '';
            }
            startDateInput.value = imageData.startDate;
            endDateInput.value = imageData.endDate;
            displayDurationInput.value = imageData.duration;
            // Ocultar input de archivo si no se permite cambiar la imagen en edición directamente
            // imageUploadModal.style.display = 'none';
        } else { // Modo añadir
            currentImageId = null;
            imageModal.querySelector('h3').textContent = 'Configurar Nueva Imagen';
            modalImagePreview.innerHTML = '';
            startDateInput.value = '';
            endDateInput.value = '';
            displayDurationInput.value = 5;
            imageUploadModal.value = ''; // Limpiar el input de archivo
            // imageUploadModal.style.display = 'block'; // Mostrar si estaba oculto
        }
        imageModal.classList.add('show');
    }

    // Función para cerrar el modal
    function closeImageModal() {
        imageModal.classList.remove('show');
    }

    // Event Listeners para abrir el modal (Añadir)
    if (addNewImageButton) {
        addNewImageButton.addEventListener('click', () => openImageModal());
    }

    // Event Listeners para abrir el modal (Editar)
    // Nota: Si estas imágenes se cargan dinámicamente,
    // necesitarás un "event delegation" para los botones de editar.
    // Para la demostración, asumimos que están en el DOM al cargar.
    document.querySelectorAll('.edit-image-button').forEach(button => {
        button.addEventListener('click', function() {
            const imageItem = this.closest('.image-item');
            const imageData = {
                id: imageItem.dataset.imageId,
                url: imageItem.querySelector('img').src,
                startDate: imageItem.dataset.startDate,
                endDate: imageItem.dataset.endDate,
                duration: imageItem.dataset.duration
            };
            openImageModal(imageData);
        });
    });
    // Si tus `.edit-image-button` se añaden dinámicamente después de la carga inicial,
    // necesitarías una delegación de eventos así:
    /*
    document.querySelector('.current-images-grid').addEventListener('click', function(event) {
        if (event.target.closest('.edit-image-button')) {
            const button = event.target.closest('.edit-image-button');
            const imageItem = button.closest('.image-item');
            const imageData = {
                id: imageItem.dataset.imageId,
                url: imageItem.querySelector('img').src,
                startDate: imageItem.dataset.startDate,
                endDate: imageItem.dataset.endDate,
                duration: imageItem.dataset.duration
            };
            openImageModal(imageData);
        }
    });
    */


    // Event Listeners para cerrar el modal
    if (closeButton) {
        closeButton.addEventListener('click', closeImageModal);
    }
    if (cancelImageConfigButton) {
        cancelImageConfigButton.addEventListener('click', closeImageModal);
    }
    window.addEventListener('click', function(event) {
        if (event.target === imageModal) {
            closeImageModal();
        }
    });

    // Previsualización de imagen en el modal
    if (imageUploadModal && modalImagePreview) {
        imageUploadModal.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    modalImagePreview.innerHTML = `<img src="${e.target.result}" alt="Previsualización">`;
                };
                reader.readAsDataURL(this.files[0]);
            } else {
                modalImagePreview.innerHTML = '';
            }
        });
    }

    // Lógica para guardar/editar (comunicación con el backend)
    if (saveImageConfigButton) {
        saveImageConfigButton.addEventListener('click', function() {
            const file = imageUploadModal.files[0];
            const startDate = startDateInput.value;
            const endDate = endDateInput.value;
            const duration = displayDurationInput.value;

            // Validación básica
            if (!file && !currentImageId) { // Si es nueva y no hay archivo
                alert('Por favor, selecciona una imagen para añadir.');
                return;
            }
            if (!startDate || !endDate || !duration) {
                alert('Por favor, completa todos los campos de fecha y duración.');
                return;
            }

            const formData = new FormData();
            if (file) {
                formData.append('image', file);
            }
            formData.append('start_date', startDate);
            formData.append('end_date', endDate);
            formData.append('duration', duration);

            let url = '/api/images';
            let method = 'POST';

            if (currentImageId) { // Editando
                url = `/api/images/${currentImageId}`;
                method = 'POST'; // Usamos POST con _method=PUT para Laravel
                formData.append('_method', 'PUT');
            }

            // Aquí enviarías los datos al servidor. Ejemplo con fetch:
            // Asegúrate de tener un meta tag para el token CSRF si usas Laravel
            // <meta name="csrf-token" content="{{ csrf_token() }}">
            /*
            fetch(url, {
                method: method,
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Imagen guardada correctamente!');
                    closeImageModal();
                    // Idealmente, actualiza el DOM aquí sin recargar toda la página
                    location.reload(); // Simple recarga para demostración
                } else {
                    alert('Error al guardar la imagen: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error al guardar la imagen:', error);
                alert('Hubo un error de red o del servidor al guardar la imagen.');
            });
            */
            alert('Datos a guardar (simulación): \nID: ' + (currentImageId || 'Nuevo') + '\nImagen: ' + (file ? file.name : 'No seleccionada') + '\nInicio: ' + startDate + '\nFin: ' + endDate + '\nDuración: ' + duration);
            closeImageModal();
            // Lógica para actualizar el DOM o recargar la página para ver los cambios
        });
    }

    // Lógica para eliminar imagen (comunicación con el backend)
    document.querySelectorAll('.delete-image-button').forEach(button => {
        button.addEventListener('click', function() {
            if (confirm('¿Estás seguro de que quieres eliminar esta imagen?')) {
                const imageItem = this.closest('.image-item');
                const imageIdToDelete = imageItem.dataset.imageId;

                // Ejemplo con fetch:
                /*
                fetch(`/api/images/${imageIdToDelete}`, {
                    method: 'POST', // O 'DELETE' si tu router Laravel soporta DELETE directo
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json' // Para el body
                    },
                    body: JSON.stringify({ _method: 'DELETE' }) // Simula DELETE para POST
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Imagen eliminada correctamente!');
                        imageItem.remove(); // Elimina el elemento del DOM
                    } else {
                        alert('Error al eliminar la imagen: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error al eliminar la imagen:', error);
                    alert('Hubo un error de red o del servidor al eliminar la imagen.');
                });
                */
                alert('Simulando eliminación de imagen con ID: ' + imageIdToDelete);
                imageItem.remove(); // Elimina visualmente para la demostración
            }
        });
    });
});