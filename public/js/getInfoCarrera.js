// getInfoCarrera.js (with added console.log statements)
document.addEventListener('DOMContentLoaded', function() {
    const carreraSelect = document.getElementById('carrera_select');
    const form = document.querySelector('form.practicante-info-wrapper');

    if (!carreraSelect || !form) {
        console.error('Elementos no encontrados: carrera_select o form.practicante-info-wrapper');
        return;
    }

    console.log('getInfoCarrera.js script loaded.');
    console.log('form.dataset.carreraRoute:', form.dataset.carreraRoute);

    const autocompletarDatosCarrera = async (carreraId) => {
        console.log('autocompletarDatosCarrera called with carreraId:', carreraId);
        if (!carreraId) {
            console.log('No carreraId provided, returning.');
            return;
        }

        try {
            const url = `${form.dataset.carreraRoute}?carrera_id=${carreraId}`;
            console.log('Fetching from URL:', url);

            const response = await fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                const errorText = await response.text();
                throw new Error(`Error en la respuesta del servidor: ${response.status} ${response.statusText} - ${errorText}`);
            }

            const data = await response.json();
            console.log('Received data:', data);

            if (data.email_institucional) {
                document.getElementById('email_institucional').value = data.email_institucional;
                console.log('Autocompleted email_institucional:', data.email_institucional);
            } else {
                console.log('No email_institucional found in data.');
            }
            if (data.telefono_institucional) {
                document.getElementById('telefono_institucional').value = data.telefono_institucional;
                console.log('Autocompleted telefono_institucional:', data.telefono_institucional);
            } else {
                console.log('No telefono_institucional found in data.');
            }

        } catch (error) {
            console.error('Error al obtener datos de la carrera:', error);
        }
    };

    carreraSelect.addEventListener('change', function() {
        console.log('Carrera select changed. New value:', this.value);
        autocompletarDatosCarrera(this.value);
    });

    if (carreraSelect.value) {
        console.log('Carrera pre-selected on load:', carreraSelect.value);
        autocompletarDatosCarrera(carreraSelect.value);
    }
});