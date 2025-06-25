<div class="image-modal" id="imageModal">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <span class="close-button">&times;</span>
        <h3 id="modalTitle">Configurar Imagen</h3>
        
        <form id="imageConfigForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="imageId" name="id">
            
            <div class="form-group">
                <label for="imageUploadModal">Seleccionar Imagen:</label>
                <input type="file" id="imageUploadModal" name="imagen" accept="image/*">
                <div id="modalImagePreview" class="modal-image-preview"></div>
            </div>
            
            <div class="form-group">
                <label for="imageTitle">Título (opcional):</label>
                <input type="text" id="imageTitle" name="titulo" class="form-control">
            </div>
            
            <div class="form-group">
                <label for="imageDescription">Descripción (opcional):</label>
                <textarea id="imageDescription" name="descripcion" class="form-control" rows="3"></textarea>
            </div>
            
            <div class="form-group">
                <label for="startDate">Fecha de Inicio:</label>
                <input type="datetime-local" id="startDate" name="fecha_inicio" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="endDate">Fecha de Fin:</label>
                <input type="datetime-local" id="endDate" name="fecha_fin" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="displayDuration">Duración de Exhibición (segundos):</label>
                <input type="number" id="displayDuration" name="duracion" min="1" value="5" class="form-control" required>
            </div>
            
            <div class="modal-actions">
                <button type="button" class="btn btn-secondary cancel-image-config-button">Cancelar</button>
                <button type="submit" class="btn btn-primary save-image-config-button">Guardar Configuración</button>
            </div>
        </form>
    </div>
</div>