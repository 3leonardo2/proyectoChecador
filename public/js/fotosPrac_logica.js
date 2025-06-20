        document.addEventListener('DOMContentLoaded', function() {
            // 1. Obtener los elementos del DOM
            const imageInput = document.getElementById('add-image-input');
            const imagePreviewContainer = document.getElementById('image-preview-container');

            // 2. Escuchar el evento 'change' en el input de la imagen
            imageInput.addEventListener('change', function(event) {
                // Obtener el archivo seleccionado por el usuario
                const file = event.target.files[0];

                // Asegurarse de que se seleccionó un archivo
                if (file) {
                    // FileReader permite leer el contenido del archivo de forma asíncrona
                    const reader = new FileReader();

                    // 3. Definir qué hacer cuando el archivo se haya cargado
                    reader.onload = function(e) {
                        // Limpiar el contenedor (quitar la imagen anterior o el ícono)
                        imagePreviewContainer.innerHTML = '';

                        // Crear un nuevo elemento <img>
                        const img = document.createElement('img');
                        
                        // Asignar la data URL del archivo cargado al 'src' de la nueva imagen
                        img.src = e.target.result;

                        // Añadir la misma clase CSS para que mantenga el estilo
                        img.className = 'profile-image';

                        // Añadir la nueva imagen al contenedor
                        imagePreviewContainer.appendChild(img);
                    };

                    // 4. Leer el archivo como una Data URL (esto dispara el evento 'onload')
                    reader.readAsDataURL(file);
                }
            });
        });