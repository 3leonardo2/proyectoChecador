// Añadir esto al final de tu <script> en modificar_avisos.blade.php
// o en un nuevo archivo JS, por ejemplo, public/js/image_management.js
// y enlazarlo con <script src="{{ asset('js/image_management.js') }}"></script>

document.addEventListener('DOMContentLoaded', function() {
    // Lógica existente para las pestañas
    const tabItems = document.querySelectorAll('.tab-item');
    const tabContents = document.querySelectorAll('.tab-content');

    tabItems.forEach(item => {
        item.addEventListener('click', function() {
            tabItems.forEach(i => i.classList.remove('active'));
            tabContents.forEach(c => c.classList.remove('active'));
            this.classList.add('active');
            const targetTabId = this.dataset.tab;
            document.getElementById(targetTabId).classList.add('active');
        });
    });

    // --- Lógica del Modal de Gestión de Imágenes ---
    const imageModal = document.getElementById('imageModal');
    const closeButton = imageModal.querySelector('.close-button');
    const addNewImageButton = document.querySelector('.add-new-image-button');
    const editImageButtons = document.querySelectorAll('.edit-image-button');
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
            // Precargar la imagen actual si existe una URL (esto dependerá de cómo la sirvas)
            if (imageData.url) {
                modalImagePreview.innerHTML = `<img src="${imageData.url}" alt="Previsualización">`;
            } else {
                modalImagePreview.innerHTML = '';
            }
            startDateInput.value = imageData.startDate;
            endDateInput.value = imageData.endDate;
            displayDurationInput.value = imageData.duration;
        } else { // Modo añadir
            currentImageId = null;
            imageModal.querySelector('h3').textContent = 'Configurar Nueva Imagen';
            modalImagePreview.innerHTML = ''; // Limpiar previsualización
            startDateInput.value = '';
            endDateInput.value = '';
            displayDurationInput.value = 5; // Valor por defecto
            imageUploadModal.value = ''; // Limpiar el input de archivo
        }
        imageModal.classList.add('show');
    }

    // Función para cerrar el modal
    function closeImageModal() {
        imageModal.classList.remove('show');
    }

    // Event Listeners para abrir el modal
    if (addNewImageButton) {
        addNewImageButton.addEventListener('click', () => openImageModal());
    }

    editImageButtons.forEach(button => {
        button.addEventListener('click', function() {
            const imageItem = this.closest('.image-item');
            const imageData = {
                id: imageItem.dataset.imageId,
                url: imageItem.querySelector('img').src, // Obtener la URL de la imagen actual
                startDate: imageItem.dataset.startDate,
                endDate: imageItem.dataset.endDate,
                duration: imageItem.dataset.duration
            };
            openImageModal(imageData);
        });
    });

    // Event Listeners para cerrar el modal
    if (closeButton) {
        closeButton.addEventListener('click', closeImageModal);
    }
    if (cancelImageConfigButton) {
        cancelImageConfigButton.addEventListener('click', closeImageModal);
    }
    // Cerrar al hacer clic fuera del contenido del modal
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

    // Lógica para guardar/editar (aquí iría la comunicación con el backend)
    if (saveImageConfigButton) {
        saveImageConfigButton.addEventListener('click', function() {
            const file = imageUploadModal.files[0];
            const startDate = startDateInput.value;
            const endDate = endDateInput.value;
            const duration = displayDurationInput.value;

            if (!file && currentImageId === null) {
                alert('Por favor, selecciona una imagen para añadir.');
                return;
            }
            if (!startDate || !endDate || !duration) {
                alert('Por favor, completa todos los campos de fecha y duración.');
                return;
            }

            // Aquí enviarías los datos al servidor usando fetch o Axios
            const formData = new FormData();
            if (file) {
                formData.append('image', file);
            }
            formData.append('start_date', startDate);
            formData.append('end_date', endDate);
            formData.append('duration', duration);

            let url = '/api/images'; // URL para añadir nueva imagen
            let method = 'POST';

            if (currentImageId) { // Si estamos editando
                url = `/api/images/${currentImageId}`; // URL para editar imagen existente
                method = 'POST'; // Laravel puede usar POST con un campo _method=PUT para simular PUT
                formData.append('_method', 'PUT');
            }

            // Ejemplo básico con fetch:
            /*
            fetch(url, {
                method: method,
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // Si usas CSRF en Laravel
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Imagen guardada correctamente!');
                    closeImageModal();
                    // Aquí deberías recargar la lista de imágenes o añadir/actualizar el item en el DOM
                    location.reload(); // Simple recarga para ver los cambios, en producción podrías hacer algo más sofisticado
                } else {
                    alert('Error al guardar la imagen: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Hubo un error de red o del servidor.');
            });
            */
            alert('Datos a guardar (simulación): \nID: ' + (currentImageId || 'Nuevo') + '\nImagen: ' + (file ? file.name : 'No seleccionada') + '\nInicio: ' + startDate + '\nFin: ' + endDate + '\nDuración: ' + duration);
            closeImageModal();
            // Lógica para actualizar el DOM o recargar la página para ver los cambios
        });
    }

    // Lógica para eliminar imagen (aquí iría la comunicación con el backend)
    document.querySelectorAll('.delete-image-button').forEach(button => {
        button.addEventListener('click', function() {
            if (confirm('¿Estás seguro de que quieres eliminar esta imagen?')) {
                const imageItem = this.closest('.image-item');
                const imageIdToDelete = imageItem.dataset.imageId;

                // Ejemplo básico con fetch:
                /*
                fetch(`/api/images/${imageIdToDelete}`, {
                    method: 'POST', // O DELETE si tu API REST lo soporta directamente
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json' // Para enviar _method en el body si es POST
                    },
                    body: JSON.stringify({ _method: 'DELETE' }) // Simula DELETE para rutas POST en Laravel
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
                    console.error('Error:', error);
                    alert('Hubo un error de red o del servidor al eliminar.');
                });
                */
                alert('Simulando eliminación de imagen con ID: ' + imageIdToDelete);
                imageItem.remove(); // Elimina visualmente para la demostración
            }
        });
    });
});