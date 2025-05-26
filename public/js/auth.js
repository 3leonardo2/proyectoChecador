document.addEventListener('DOMContentLoaded', function() {
    // Efecto de hover en el contenedor del login
    const loginContainer = document.querySelector('.login-container');
    
    loginContainer.addEventListener('mouseenter', () => {
        loginContainer.style.transform = 'translateY(-5px)';
    });
    
    loginContainer.addEventListener('mouseleave', () => {
        loginContainer.style.transform = 'translateY(0)';
    });
    
    // Validación básica del formulario
    const loginForm = document.getElementById('loginForm');
    
    loginForm.addEventListener('submit', function(e) {
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value.trim();
        
        if (!email || !password) {
            e.preventDefault();
            alert('Por favor complete todos los campos');
            return;
        }
        
        // Aquí puedes agregar más validaciones si necesitas
    });
    
    // Efecto de carga al enviar el formulario
    loginForm.addEventListener('submit', function() {
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.innerHTML = '<span class="spinner"></span> Iniciando sesión...';
        submitBtn.disabled = true;
    });
    
    // Animación para los inputs al cargar la página
    const inputs = document.querySelectorAll('.form-group input');
    inputs.forEach((input, index) => {
        setTimeout(() => {
            input.style.opacity = '1';
            input.style.transform = 'translateY(0)';
        }, index * 100);
        
        // Estilos iniciales para la animación
        input.style.opacity = '0';
        input.style.transform = 'translateY(10px)';
        input.style.transition = 'all 0.3s ease ' + (index * 0.1) + 's';
    });
    
    // Escapar de pantalla completa (como menciona tu imagen)
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (document.fullscreenElement) {
                document.exitFullscreen();
            }
        }
    });
});