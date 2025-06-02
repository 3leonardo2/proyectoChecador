  const menuButton = document.getElementById('menuButton');
        const menuModal = document.getElementById('menuModal');
        const modalOverlay = document.querySelector('.modal-overlay');

        menuButton.addEventListener('click', () => {
            menuModal.classList.add('show');
            document.body.style.overflow = 'hidden'; // Evita el scroll del body
        });

        // Cierra el modal al hacer clic en el overlay o fuera del contenido del menú
        modalOverlay.addEventListener('click', () => {
            menuModal.classList.remove('show');
            document.body.style.overflow = ''; // Restaura el scroll del body
        });

        // Opcional: Cerrar el modal al hacer clic en un elemento del menú
        const menuItems = document.querySelectorAll('.menu-item');
        menuItems.forEach(item => {
            item.addEventListener('click', () => {
                // menuModal.classList.remove('show');
                // document.body.style.overflow = '';
                // Puedes decidir si quieres que se cierre automáticamente o no
                // Depende de si la navegación ocurre al hacer clic en el item
            });
        });

        // Opcional: Cerrar con la tecla Esc
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && menuModal.classList.contains('show')) {
                menuModal.classList.remove('show');
                document.body.style.overflow = '';
            }
        });
