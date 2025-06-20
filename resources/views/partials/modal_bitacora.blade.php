
<div class="modal fade" id="bitacoraModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bitacoraModalTitle">Notificación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="bitacoraModalBody">
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-custom2 rounded-pill" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
    function showModal(title, message, isSuccess) {
        const modal = new bootstrap.Modal(document.getElementById('bitacoraModal'));
        const modalTitle = document.getElementById('bitacoraModalTitle');
        const modalBody = document.getElementById('bitacoraModalBody');
        
        modalTitle.textContent = title;
        modalBody.innerHTML = message;
        
        // Cambiar color según éxito/error
        if(isSuccess) {
            modalBody.classList.add('text-success');
            modalBody.classList.remove('text-danger');
        } else {
            modalBody.classList.add('text-danger');
            modalBody.classList.remove('text-success');
        }
        
        modal.show();
        
        // Cerrar automáticamente después de 10 segundos
        setTimeout(() => {
            modal.hide();
        }, 5000);
    }
</script>