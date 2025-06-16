<div class="image-modal" id="imageModal">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <span class="close-button">&times;</span>
        <h3>Configurar Imagen</h3> {{-- Este título se cambiará con JS --}}
        <div class="form-group">
            <label for="imageUploadModal">Seleccionar Imagen:</label>
            <input type="file" id="imageUploadModal" accept="image/*">
            <div id="modalImagePreview" class="modal-image-preview">
                </div>
        </div>
        <div class="form-group">
            <label for="startDate">Fecha de Inicio:</label>
            <input type="date" id="startDate">
        </div>
        <div class="form-group">
            <label for="endDate">Fecha de Fin:</label>
            <input type="date" id="endDate">
        </div>
        <div class="form-group">
            <label for="displayDuration">Duración de Exhibición (segundos por ejemplo):</label>
            <input type="number" id="displayDuration" min="1" value="5">
        </div>
        <div class="modal-actions">
            <button class="save-image-config-button">Guardar Configuración</button>
            <button class="cancel-image-config-button">Cancelar</button>
        </div>
    </div>
</div>