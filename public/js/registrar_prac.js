$(document).ready(function() {
    const $institucionSelect = $('#institucion_select');
    const $carreraSelect = $('#carrera_select');
    const $loadingCarreras = $('.loading-carreras');
    const $noCarrerasMessage = $('.no-carreras-message');

    // Cargar carreras cuando se selecciona una institución
    $institucionSelect.on('change', function() {
        const institucionId = $(this).val();
        
        if (!institucionId) {
            $carreraSelect.empty().append('<option value="">Primero seleccione una institución</option>').prop('disabled', true);
            return;
        }

        // Mostrar spinner
        $loadingCarreras.show();
        $carreraSelect.empty().prop('disabled', true);
        $noCarrerasMessage.hide();

        // Hacer la petición AJAX - URL modificada para coincidir con tu ruta definida
        $.ajax({
            url: `/instituciones/${institucionId}/carreras`, // Asegúrate que esta ruta coincida con tu backend
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
        const currentCarreraId = "{{ $practicante->carrera_id ?? '' }}";
        if (currentCarreraId) {
            $carreraSelect.val(currentCarreraId);
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
    });
});