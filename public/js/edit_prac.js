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
            url: `/instituciones/${institucionId}/carreras`, // Ensure this URL matches your backend route
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

                    // Set the previously selected career if provided
                    if (selectedCarreraId) {
                        $carreraSelect.val(selectedCarreraId);
                    }
                    // Trigger change event for getInfoCarrera.js to autocomplete institutional data
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
        loadCarreras(institucionId); // When user changes, no need for pre-selected career
    });

    // --- IMPORTANT: Initial load logic for edit view ---
    const initialInstitucionId = $institucionSelect.val();
    // Get the current practitioner's career_id for pre-selection
    // Make sure $practicante is available in your Blade view
    const initialCarreraId = "{{ old('carrera_id', $practicante->carrera_id ?? '') }}";

    if (initialInstitucionId) {
        // If an institution is already selected on page load,
        // load its careers and pre-select the practitioner's career
        loadCarreras(initialInstitucionId, initialCarreraId);
    }
});