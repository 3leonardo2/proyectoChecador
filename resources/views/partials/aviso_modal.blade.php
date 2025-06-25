<div class="image-modal" id="avisoModal" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <span class="close-button">&times;</span>
        <div class="modal-header">
            <h3 id="avisoModalTitle">Añadir Nuevo Aviso</h3>
        </div>
        <div class="modal-body">
            <form id="avisoForm" method="POST">
                @csrf
                <input type="hidden" name="_method" id="avisoMethod" value="POST">
                <input type="hidden" name="aviso_id" id="avisoId">
                
                <div class="form-group">
                    <label for="contenido">Contenido del Aviso</label>
                    <textarea class="form-control" id="contenido" name="contenido" rows="3" required></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="fecha_inicio">Fecha de Inicio</label>
                        <input type="datetime-local" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="fecha_fin">Fecha de Fin</label>
                        <input type="datetime-local" class="form-control" id="fecha_fin" name="fecha_fin" required>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-actions">
            <button type="button" class="btn btn-secondary cancel-aviso-button">Cancelar</button>
            <button type="submit" class="btn btn-primary save-aviso-button" form="avisoForm">Guardar</button>
        </div>
    </div>
</div>