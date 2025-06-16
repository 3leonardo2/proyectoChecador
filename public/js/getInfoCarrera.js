document.addEventListener('DOMContentLoaded', function() {
    const carreraInput = document.getElementById('carrera_nombre');
    const form = document.querySelector('form.practicante-info-wrapper');
    
    if (!form) {
        console.error('Formulario no encontrado');
        return;
    }
    
    const getByCarreraRoute = form.dataset.carreraRoute;
    
    carreraInput.addEventListener('change', async function() {
        const carrera = this.value.trim();
        
        if (!carrera) {
            console.log('Campo de carrera vacío');
            return;
        }
        
        try {
            console.log('Buscando información para:', carrera);
            
            const response = await fetch(`${getByCarreraRoute}?carrera=${encodeURIComponent(carrera)}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.error || `Error HTTP: ${response.status}`);
            }
            
            const data = await response.json();
            console.log('Datos recibidos:', data);
            
            // Actualizar campos
            const emailInput = document.getElementById('email_institucional');
            const telInput = document.getElementById('telefono_institucional');
            
            emailInput.value = data.email_institucional || '';
            telInput.value = data.telefono_institucional || '';
            
        } catch (error) {
            console.error('Error en la solicitud:', error);
            alert('Error: ' + error.message);
        }
    });
});