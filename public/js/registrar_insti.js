 document.addEventListener('DOMContentLoaded', function () {
            const carrerasContainer = document.getElementById('carreras-container');
            const addCareerButton = document.getElementById('add-career-button');
            let careerIndex = 0; // Empezamos con el índice 0 para el primer bloque ya existente

            // Función para actualizar los índices de los bloques de carrera
            function updateCareerIndices() {
                const careerBlocks = carrerasContainer.querySelectorAll('.carrera-block');
                careerBlocks.forEach((block, newIndex) => {
                    block.setAttribute('data-index', newIndex);
                    block.querySelectorAll('input, select, textarea').forEach(input => {
                        // Actualizar el ID
                        const oldId = input.id;
                        if (oldId) {
                            input.id = oldId.replace(/_\d+$/, `_${newIndex}`);
                        }
                        // Actualizar el NAME
                        const name = input.name;
                        if (name) {
                            input.name = name.replace(/\[\d+\]/, `[${newIndex}]`);
                        }
                        // Actualizar el FOR del label si existe
                        const label = block.querySelector(`label[for="${oldId}"]`);
                        if (label) {
                            label.htmlFor = input.id;
                        }
                    });
                    // Mostrar botón de eliminar para los bloques añadidos
                    const removeButton = block.querySelector('.remove-career-button');
                    if (removeButton && newIndex > 0) { // Solo mostrar si no es el primer bloque
                        removeButton.style.display = 'inline-block';
                    } else if (removeButton && newIndex === 0) {
                        removeButton.style.display = 'none'; // Ocultar para el primer bloque si se re-indexa a 0
                    }
                });
                careerIndex = careerBlocks.length -1; // Actualizar el índice global
            }


            addCareerButton.addEventListener('click', function () {
                careerIndex++; // Incrementa el índice para la nueva carrera
                const newCareerBlock = carrerasContainer.firstElementChild.cloneNode(true); // Clona el primer bloque de carrera
                
                newCareerBlock.setAttribute('data-index', careerIndex); // Actualiza el data-index

                // Limpiar los valores de los inputs clonados
                newCareerBlock.querySelectorAll('input').forEach(input => {
                    input.value = ''; // Limpia el valor
                    const oldId = input.id;
                    if (oldId) { // Actualiza el ID si existe
                        input.id = oldId.replace(/_\d+$/, `_${careerIndex}`);
                    }
                    const name = input.name;
                    if (name) { // Actualiza el name, que es crucial para el backend
                        input.name = name.replace(/\[\d+\]/, `[${careerIndex}]`);
                    }
                    // Actualizar el atributo 'for' de los labels correspondientes
                    const label = newCareerBlock.querySelector(`label[for="${oldId}"]`);
                    if (label) {
                        label.htmlFor = input.id;
                    }
                });

                // Añadir botón de eliminar al nuevo bloque de carrera
                let removeButton = newCareerBlock.querySelector('.remove-career-button');
                if (!removeButton) { // Si el template no tiene botón, lo creamos
                    removeButton = document.createElement('button');
                    removeButton.type = 'button';
                    removeButton.classList.add('remove-career-button');
                    removeButton.textContent = 'Eliminar Carrera';
                    newCareerBlock.appendChild(removeButton); // Añade el botón al final del bloque
                }
                removeButton.style.display = 'inline-block'; // Asegurarse que sea visible

                removeButton.addEventListener('click', function () {
                    newCareerBlock.remove();
                    updateCareerIndices(); // Re-indexar después de eliminar
                });

                carrerasContainer.appendChild(newCareerBlock);
                updateCareerIndices(); // Re-indexar después de añadir
            });
        });