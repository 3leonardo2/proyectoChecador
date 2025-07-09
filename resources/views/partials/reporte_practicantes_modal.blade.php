<div class="custom-modal" id="reporteModal" aria-hidden="true">
    <div class="custom-modal-overlay" id="modalOverlay"></div>
    <div class="custom-modal-content">
        <div class="custom-modal-header">
            <h5 class="modal-title">Opciones de Reporte</h5>
            <button type="button" class="custom-modal-close" id="closeModal">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="custom-modal-body">
            <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" id="incluirRevisiones" name="incluir_revisiones" checked>
                <label class="form-check-label" for="incluirRevisiones">Incluir revisiones</label>
            </div>
        </div>
        <div class="custom-modal-footer">
            <button type="button" class="btn btn-secondary" id="cancelModal">
                <i class="fas fa-times me-1"></i> Cancelar
            </button>
            <button type="button" class="btn btn-primary" id="generateReport">
                <i class="fas fa-file-pdf me-1"></i> Generar PDF
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elementos del modal
    const reporteModal = document.getElementById('reporteModal');
    const modalOverlay = document.getElementById('modalOverlay');
    const closeModal = document.getElementById('closeModal');
    const cancelModal = document.getElementById('cancelModal');
    const generateReport = document.getElementById('generateReport');
    const reporteButton = document.querySelector('.reporte-button');

    // Función para abrir el modal
    function openModal() {
        reporteModal.style.display = 'flex';
        document.body.style.overflow = 'hidden'; // Evita el scroll del body
    }

    // Función para cerrar el modal
    function closeModalFunc() {
        reporteModal.style.animation = 'modalFadeOut 0.3s ease-out';
        setTimeout(() => {
            reporteModal.style.display = 'none';
            document.body.style.overflow = 'auto'; // Restaura el scroll
        }, 300);
    }

    // Eventos
    reporteButton.addEventListener('click', openModal);
    closeModal.addEventListener('click', closeModalFunc);
    cancelModal.addEventListener('click', closeModalFunc);
    modalOverlay.addEventListener('click', closeModalFunc);

    // Función para generar el reporte (la misma que tenías)
    generateReport.addEventListener('click', function() {
        const incluirRevisiones = document.getElementById('incluirRevisiones').checked;
        window.location.href = `/practicantes/${practicanteId}/reporte?incluir_revisiones=${incluirRevisiones}`;
    });
});
</script>