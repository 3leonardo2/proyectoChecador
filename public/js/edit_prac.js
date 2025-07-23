console.log("edit_prac.js script loaded and ready!");

$(document).ready(function() {
    const $institucionSelect = $('#institucion_select');
    const $carreraSelect = $('#carrera_select');
    const $loadingCarreras = $('.loading-carreras');
    const $noCarrerasMessage = $('.no-carreras-message');

    // Function to load careers based on institution ID
    function loadCarreras(institucionId, selectedCarreraId = null) {
    if (!institucionId) {
        $carreraSelect.empty().append('<option value="">Primero seleccione una institución</option>').prop('disabled', true);
        return;
    }

    $loadingCarreras.show();
    $carreraSelect.empty().prop('disabled', true);
    $noCarrerasMessage.hide();

    $.ajax({
        url: `/instituciones/${institucionId}/carreras`,
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            $carreraSelect.empty();

            if (response.success && response.carreras.length > 0) {
                $carreraSelect.append('<option value="">Seleccione una carrera</option>');

                response.carreras.forEach(function(carrera) {
                    $carreraSelect.append(
                        $('<option></option>')
                            .val(carrera.id_carrera)
                            .text(carrera.nombre_carr)
                    );
                });
                $carreraSelect.prop('disabled', false);

                // Solo establecer el valor inicial si no estamos cambiando de institución
                if (selectedCarreraId && $institucionSelect.val() == initialInstitucionId) {
                    $carreraSelect.val(selectedCarreraId);
                }
                $carreraSelect.trigger('change');

            } else {
                $carreraSelect.append('<option value="">No hay carreras disponibles</option>');
                $noCarrerasMessage.show();
            }
        },
        error: function(xhr, status, error) {
            console.error("Error al cargar carreras:", error);
            $carreraSelect.append('<option value="">Error al cargar carreras</option>');
            $noCarrerasMessage.show();
        },
        complete: function() {
            $loadingCarreras.hide();
        }
    });
}

    // Attach change event listener to institution select
    $institucionSelect.on('change', function() {
        const institucionId = $(this).val();
        loadCarreras(institucionId);
    });

    // Initial load logic
    const initialInstitucionId = $institucionSelect.val();
    const initialCarreraId = <?php echo json_encode($practicante->carrera_id ?? ''); ?>;
    console.log('Initial institution ID:', initialInstitucionId, 'Initial carrera ID:', initialCarreraId);

    console.log('Initial values:', {
        institucionId: initialInstitucionId,
        carreraId: initialCarreraId
    });

if (initialInstitucionId) {
    // Forzar un pequeño retraso para asegurar que el DOM esté listo
    setTimeout(() => {
        loadCarreras(initialInstitucionId, true);
        
        // Seleccionar la carrera después de que se carguen las opciones
        setTimeout(() => {
            if (initialCarreraId) {
                $carreraSelect.val(initialCarreraId);
                console.log('Carrera seleccionada:', initialCarreraId);
            }
        }, 300);
    }, 100);
}
});