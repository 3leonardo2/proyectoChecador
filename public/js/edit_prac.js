$(document).ready(function() {
    // Cache de carreras por institución
    const carrerasCache = {};
    
    // Elementos del DOM
    const elements = {
        form: $('.practicante-info-wrapper'),
        institucion: {
            input: $('#institucion_nombre'),
            dropdown: $('#instituciones-dropdown'),
            items: $('.institution-select-item')
        },
        carrera: {
            input: $('#carrera_nombre'),
            dropdown: $('#carreras-dropdown'),
            spinner: $('.loading-carreras'),
            noResults: $('.no-carreras-message')
        },
        contacto: {
            email: $('#email_institucional'),
            telefono: $('#telefono_institucional')
        }
    };

    // Inicializar el formulario de edición
    function initEditForm() {
        const institucionId = $('#institucion_id').val();
        if (institucionId) {
            loadCarreras(institucionId);
        }
    }

    // Eventos para el selector de instituciones
    elements.institucion.input.on('focus click', function() {
        showInstitucionesDropdown();
        // Limpiar el filtro al hacer focus
        elements.institucion.input.val('');
        filterInstituciones();
    }).on('input', filterInstituciones);
    
    elements.institucion.items.on('click', selectInstitucion);
    
    // Eventos para el selector de carreras
    elements.carrera.input.on('focus click', showCarrerasDropdown)
                          .on('input', autocompletarInfoContacto);
    
    $(document).on('click', closeAllDropdowns);

    // Funciones principales
    function showInstitucionesDropdown() {
        elements.institucion.dropdown.show();
        filterInstituciones();
    }

    function filterInstituciones() {
        const searchTerm = elements.institucion.input.val().toLowerCase();
        elements.institucion.items.each(function() {
            $(this).toggle($(this).text().toLowerCase().includes(searchTerm));
        });
    }

    function selectInstitucion() {
        const $this = $(this);
        elements.institucion.input.val($this.data('value'));
        $('#institucion_id').val($this.data('id'));
        elements.institucion.dropdown.hide();
        loadCarreras($this.data('id'));
    }

    function showCarrerasDropdown() {
        if (!elements.carrera.input.prop('disabled')) {
            elements.carrera.dropdown.show();
        }
    }

    function closeAllDropdowns(e) {
        if (!$(e.target).closest('.institution-select-container, .carrera-select-container').length) {
            elements.institucion.dropdown.hide();
            elements.carrera.dropdown.hide();
        }
    }

    function loadCarreras(institucionId) {
        // Reset estado
        elements.carrera.spinner.show();
        elements.carrera.input.prop('disabled', true).val('Cargando carreras...');
        elements.carrera.noResults.hide();
        elements.carrera.dropdown.empty().hide();

        // Verificar cache
        if (carrerasCache[institucionId]) {
            updateCarrerasDropdown(carrerasCache[institucionId]);
            return;
        }

        // AJAX para carreras
        $.ajax({
            url: `/instituciones/${institucionId}/carreras`,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success && response.carreras?.length) {
                    carrerasCache[institucionId] = response.carreras;
                    updateCarrerasDropdown(response.carreras);
                    
                    // Seleccionar automáticamente la carrera actual si existe
                    const carreraId = $('#carrera_id').val();
                    if (carreraId) {
                        const carrera = response.carreras.find(c => c.id_carrera == carreraId);
                        if (carrera) {
                            elements.carrera.input.val(carrera.nombre_carr);
                        }
                    }
                } else {
                    showNoCarrerasMessage();
                }
            },
            error: showNoCarrerasMessage,
            complete: function() {
                elements.carrera.spinner.hide();
            }
        });
    }

    function updateCarrerasDropdown(carreras) {
        elements.carrera.dropdown.empty();
        
        carreras.forEach(carrera => {
            elements.carrera.dropdown.append(
                $(`<div class="carrera-select-item">`)
                    .data('value', carrera.nombre_carr)
                    .data('id', carrera.id_carrera)
                    .text(carrera.nombre_carr)
                    .on('click', selectCarrera)
            );
        });
        
        elements.carrera.input.prop('disabled', false)
            .val('')
            .attr('placeholder', 'Selecciona una carrera');
    }

    function selectCarrera() {
        const $this = $(this);
        elements.carrera.input.val($this.data('value'));
        $('#carrera_id').val($this.data('id'));
        elements.carrera.dropdown.hide();
        autocompletarInfoContacto();
    }

    function showNoCarrerasMessage() {
        elements.carrera.dropdown.empty().hide();
        elements.carrera.input.prop('disabled', true).val('No hay carreras disponibles');
        elements.carrera.noResults.show();
    }

    function autocompletarInfoContacto() {
        const carreraNombre = elements.carrera.input.val();
        if (!carreraNombre) return;

        $.ajax({
            url: elements.form.data('carrera-route'),
            method: 'GET',
            data: { carrera: carreraNombre },
            dataType: 'json',
            success: function(response) {
                if (response.email_institucional) {
                    elements.contacto.email.val(response.email_institucional);
                }
                if (response.telefono_institucional) {
                    elements.contacto.telefono.val(response.telefono_institucional);
                }
            }
        });
    }

    // Inicializar el formulario al cargar
    initEditForm();
});