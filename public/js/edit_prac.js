$(document).ready(function() {
    console.log("edit_prac.js script loaded and ready!");

    const $institucionSelect = $('#institucion_select');
    const $carreraSelect = $('#carrera_id'); // Cambiado a 'carrera_id'
    const $loadingCarreras = $('.loading-carreras');
    const $noCarrerasMessage = $('.no-carreras-message');

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

                    $.each(response.carreras, function(index, carrera) {
                        $carreraSelect.append(
                            $('<option></option>')
                                .val(carrera.id_carrera)
                                .text(carrera.nombre_carr)
                        );
                    });
                    $carreraSelect.prop('disabled', false);

                    if (selectedCarreraId) {
                        $carreraSelect.val(selectedCarreraId).trigger('change');
                    }
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

    $institucionSelect.on('change', function() {
        loadCarreras($(this).val());
    });

    // Inicialización
    const initialInstitucionId = $institucionSelect.val();
    const initialCarreraId = $carreraSelect.val();

    if (initialInstitucionId) {
        loadCarreras(initialInstitucionId, initialCarreraId);
    }
});