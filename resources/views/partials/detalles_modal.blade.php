<div class="alert-modal" id="alertModal" style="display: none;">
    <div class="alert-modal-content">
        <div class="alert-modal-icon" id="alertModalIcon">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="alert-modal-message" id="alertModalMessage"></div>
        <button class="alert-modal-close" id="alertModalClose">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>

<style>
.alert-modal {
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 1000;
    animation: slideDown 0.5s ease-out;
    max-width: 90%; /* Ajusta según necesites */
    width: auto; /* Ancho automático basado en el contenido */
}

.alert-modal-content {
    padding: 15px 25px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    display: flex;
    align-items: center;
    width: 100%; /* Ocupa todo el ancho del contenedor padre */
}

/* Elimina el borde izquierdo y ajusta los colores */
.alert-modal.success .alert-modal-content {
    background-color: #d4edda;
    color: #155724;
}

.alert-modal.error .alert-modal-content {
    background-color: #f8d7da;
    color: #721c24;
}

/* Resto del CSS se mantiene igual */
.alert-modal-icon {
    margin-right: 15px;
    font-size: 24px;
}

.alert-modal-message {
    flex-grow: 1;
    font-size: 16px;
    font-weight: 500;
}

.alert-modal-close {
    background: none;
    border: none;
    margin-left: 15px;
    cursor: pointer;
    font-size: 18px;
    opacity: 0.7;
    transition: opacity 0.2s;
}

.alert-modal-close:hover {
    opacity: 1;
}

@keyframes slideDown {
    from {
        transform: translate(-50%, -100%);
        opacity: 0;
    }
    to {
        transform: translate(-50%, 0);
        opacity: 1;
    }
}

@keyframes fadeOut {
    to {
        opacity: 0;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('alertModal');
    const closeBtn = document.getElementById('alertModalClose');
    
    closeBtn.addEventListener('click', function() {
        modal.style.animation = 'fadeOut 0.5s ease-out';
        setTimeout(() => {
            modal.style.display = 'none';
        }, 500);
    });
    
    if (modal.style.display === 'block') {
        setTimeout(() => {
            modal.style.animation = 'fadeOut 0.5s ease-out';
            setTimeout(() => {
                modal.style.display = 'none';
            }, 500);
        }, 5000);
    }
});
</script>