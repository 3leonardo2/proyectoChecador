$(document).ready(function () {
    const $institucionSelect = $("#institucion_select");
    const $carreraSelect = $("#carrera_select");
    const $loadingCarreras = $(".loading-carreras");
    const $noCarrerasMessage = $(".no-carreras-message");

    // Recupera los valores almacenados en localStorage
    const tempInstitucionId = localStorage.getItem('institucion_id') || $("form").data("old-institucion-id") || "";
    const tempCarreraId = localStorage.getItem('carrera_id') || $("form").data("old-carrera-id") || "";

    // Cargar carreras cuando se selecciona una institución
    $institucionSelect.on("change", function () {
        const institucionId = $(this).val();

        if (!institucionId) {
            $carreraSelect
                .empty()
                .append('<option value="">Primero seleccione una institución</option>');
            return;
        }

        $loadingCarreras.show();
        $noCarrerasMessage.hide();

        $.ajax({
            url: `/instituciones/${institucionId}/carreras`,
            method: "GET",
            dataType: "json",
            success: function (response) {
                $carreraSelect.empty();

                if (response.success && response.carreras.length > 0) {
                    $carreraSelect.append('<option value="">Seleccione una carrera</option>');
                    response.carreras.forEach(function (carrera) {
                        $carreraSelect.append(
                            $("<option></option>")
                                .val(carrera.id_carrera)
                                .text(carrera.nombre_carr)
                        );
                    });
                    // Restaurar selección previa si existe
                    setTimeout(function() {
                        if (tempCarreraId) {
                            $carreraSelect.val(tempCarreraId);
                        }
                    }, 0); // Forzar a que el valor se asigne justo después de poblar
                } else {
                    $carreraSelect.append('<option value="">No hay carreras disponibles</option>');
                    $noCarrerasMessage.show();
                }
            },
            error: function (xhr, status, error) {
                console.error("Error al cargar carreras:", error);
                $carreraSelect.append('<option value="">Error al cargar carreras</option>');
                $noCarrerasMessage.show();
            },
            complete: function () {
                $loadingCarreras.hide();
            },
        });
    });

    // Al cargar la página, si hay una institución seleccionada previamente, dispara el evento 'change'
    if (tempInstitucionId) {
        $institucionSelect.val(tempInstitucionId).trigger("change");
    }

    // Limpia localStorage si no hay datos previos (nuevo registro)
    if (
        !$("form").data("old-institucion-id") &&
        !$("form").data("old-carrera-id")
    ) {
        localStorage.removeItem('institucion_id');
        localStorage.removeItem('carrera_id');
    }

    // Guarda los valores seleccionados en localStorage al enviar el formulario
    $('form').on('submit', function() {
        localStorage.setItem('institucion_id', $('#institucion_select').val());
        localStorage.setItem('carrera_id', $('#carrera_select').val());
    });
});