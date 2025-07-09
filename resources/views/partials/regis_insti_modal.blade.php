<div id="confirmationModal" class="confirmation-modal">
    <span id="confirmationModalIcon"></span>
    <span id="confirmationModalMessage"></span>
</div>

<style>
    /* Estilos para el modal de confirmación centrado */
    .confirmation-modal {
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        padding: 15px 30px;
        border-radius: 5px;
        display: none;
        align-items: center;
        gap: 15px;
        z-index: 9999;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        max-width: 80%;
        text-align: center;
    }
    
    .confirmation-modal.success {
        background-color: #d4edda;
        color: #155724;
        border-left: 5px solid #28a745;
    }
    
    .confirmation-modal.error {
        background-color: #f8d7da;
        color: #721c24;
        border-left: 5px solid #dc3545;
    }
    
    .confirmation-modal i {
        font-size: 24px;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-20px) translateX(-50%); }
        to { opacity: 1; transform: translateY(0) translateX(-50%); }
    }
    
    @keyframes fadeOut {
        from { opacity: 1; transform: translateY(0) translateX(-50%); }
        to { opacity: 0; transform: translateY(-20px) translateX(-50%); }
    }
</style>

<script>
    // Función para mostrar el modal de confirmación
    function showConfirmationModal(type, message, icon = null) {
        const modal = document.getElementById('confirmationModal');
        const modalIcon = document.getElementById('confirmationModalIcon');
        const modalMessage = document.getElementById('confirmationModalMessage');
        
        // Configura el modal
        modal.className = `confirmation-modal ${type}`;
        modalIcon.innerHTML = icon ? `<i class="fas ${icon}"></i>` : 
            (type === 'success' ? '<i class="fas fa-check-circle"></i>' : '<i class="fas fa-exclamation-circle"></i>');
        modalMessage.textContent = message;
        
        // Muestra el modal con animación
        modal.style.display = 'flex';
        modal.style.animation = 'fadeIn 0.5s ease-out forwards';
        
        // Cierra después de 5 segundos (ajustable)
        setTimeout(() => {
            modal.style.animation = 'fadeOut 0.5s ease-out forwards';
            setTimeout(() => {
                modal.style.display = 'none';
            }, 500);
        }, 5000);
    }
    
    // Manejar el envío del formulario
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('.registrar_insti-wrapper form');
        
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const submitButton = form.querySelector('button[type="submit"]');
                const originalText = submitButton.innerHTML;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
                submitButton.disabled = true;
                
                fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        showConfirmationModal('success', data.message, 'fa-university');
                        // Resetear el formulario después de 3 segundos
                        setTimeout(() => {
                            form.reset();
                        }, 3000);
                    } else {
                        showConfirmationModal('error', data.message || 'Ocurrió un error al guardar');
                    }
                })
                .catch(error => {
                    showConfirmationModal('error', 'Error de conexión. Por favor intenta nuevamente.');
                    console.error('Error:', error);
                })
                .finally(() => {
                    submitButton.innerHTML = originalText;
                    submitButton.disabled = false;
                });
            });
        }
        
        // Manejar mensajes de sesión (si vienen del servidor)
        @if(session('success'))
            showConfirmationModal('success', '{{ session('success') }}', 'fa-university');
        @endif
        
        @if(session('error'))
            showConfirmationModal('error', '{{ session('error') }}');
        @endif
    });
</script>