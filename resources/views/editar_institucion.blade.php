<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Institución</title>
    <link rel="stylesheet" href="{{ asset('css/registrar_insti.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/menu_modal.css') }}">

    <style>
        .delete-carrera {
            color: #ff0000;
            cursor: pointer;
            margin-left: 10px;
        }
    </style>
</head>

<body>
    <div class="header">
        @include('partials.menu_modal')
        @include('partials.regis_insti_modal')
        <a href="{{ route('instituciones.index') }}" class="back-button">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1>Editar Institución</h1>
        <button class="menu-button" id="menuButton">
            <i class="fa-solid fa-bars"></i>
        </button>
    </div>

    <div class="main-container">
        <form action="{{ route('instituciones.update', $institucion->id_institucion) }}" method="POST"
            class="registrar_insti-wrapper" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="institucion-fixed-elements">
                <div class="institucion-profile-section">
                    <div class="profile-image-container">
                        @if ($institucion->imagen)
                            <img src="{{ asset('storage/' . $institucion->imagen) }}" alt="ImagenInstitución"
                                class="profile-image">
                        @endif
                    </div>
                    <input type="file" id="add-image-input" class="add-image-input" name="profile_image"
                        accept="image/*" style="display: none;">
                    <label for="add-image-input" class="add-image-button">Cambiar imagen</label>
                    <div class="mensaje-opcional">(OPCIONAL)</div>
                </div>
            </div>
            <div class="institucion-scrollable-content">
                <h2>Información de la institución</h2>
                <div class="form-group">
                    <label for="nombre_ins">Nombre*:</label>
                    <input type="text" id="nombre_ins" name="nombre" placeholder="Nombre..." required
                        value="{{ old('nombre', $institucion->nombre) }}">
                </div>
                <div class="form-group">
                    <label for="direccion_ins">Dirección*:</label>
                    <input type="text" id="direccion_ins" name="direccion" placeholder="Dirección..." required
                        value="{{ old('direccion', $institucion->direccion) }}">
                </div>
                <div class="form-group">
                    <label for="telefono_ins">Teléfono de contacto*:</label>
                    <input type="text" id="telefono_ins" name="telefono" placeholder="Teléfono..."
                        value="{{ old('telefono', $institucion->telefono) }}">
                </div>
                <div class="form-group">
                    <label for="correo_ins">Correo electrónico*:</label>
                    <input type="email" id="correo_ins" name="correo" placeholder="Correo electrónico..."
                        value="{{ old('correo', $institucion->correo) }}">
                </div>

                <h2>Carreras:</h2>
                <div id="carreras-container">
                    @foreach ($institucion->carreras as $index => $carrera)
                        <div class="carrera-block" data-index="{{ $index }}">
                            <input type="hidden" name="carreras[{{ $index }}][id_carrera]"
                                value="{{ $carrera->id_carrera }}">
                            <div class="form-group">
                                <label for="nombre_carr_{{ $index }}">Nombre:</label>
                                <input type="text" id="nombre_carr_{{ $index }}"
                                    name="carreras[{{ $index }}][nombre_carr]"
                                    placeholder="Nombre de carrera..."
                                    value="{{ old('carreras.' . $index . '.nombre_carr', $carrera->nombre_carr) }}">
                            </div>
                            <div class="form-group">
                                <label for="gerente_carr_{{ $index }}">Gerente:</label>
                                <input type="text" id="gerente_carr_{{ $index }}"
                                    name="carreras[{{ $index }}][gerente_carr]"
                                    placeholder="Gerente de carrera..."
                                    value="{{ old('carreras.' . $index . '.gerente_carr', $carrera->gerente_carr) }}">
                            </div>
                            <div class="form-group">
                                <label for="telefono_carr_{{ $index }}">Teléfono:</label>
                                <input type="text" id="telefono_carr_{{ $index }}"
                                    name="carreras[{{ $index }}][telefono_carr]"
                                    placeholder="Teléfono de carrera..."
                                    value="{{ old('carreras.' . $index . '.telefono_carr', $carrera->tel_gerente) }}">
                            </div>
                            <div class="form-group">
                                <label for="correo_carr_{{ $index }}">Correo:</label>
                                <input type="email" id="correo_carr_{{ $index }}"
                                    name="carreras[{{ $index }}][correo_carr]"
                                    placeholder="Correo de carrera..."
                                    value="{{ old('carreras.' . $index . '.correo_carr', $carrera->correo_carr) }}">
                                <span class="delete-carrera" onclick="eliminarCarrera(this)"><i
                                        class="fa-solid fa-trash"></i> Eliminar</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <button type="button" id="add-career-button" class="add-carrera">Añadir otra carrera</button>

                <div class="form-actions">
                    <button type="submit" class="save-button">Guardar Cambios</button>
                    <button type="button" class="cancel-button"
                        onclick="window.location='{{ route('instituciones.index') }}'">Cancelar</button>
                </div>
            </div>
        </form>
    </div>

    <script>
        // Función para añadir nueva carrera
        document.getElementById('add-career-button').addEventListener('click', function() {
            const container = document.getElementById('carreras-container');
            const index = container.children.length;

            const newBlock = document.createElement('div');
            newBlock.className = 'carrera-block';
            newBlock.dataset.index = index;

            newBlock.innerHTML = `
                <div class="form-group">
                    <label for="nombre_carr_${index}">Nombre:</label>
                    <input type="text" id="nombre_carr_${index}" name="carreras[${index}][nombre_carr]" placeholder="Nombre de carrera...">
                </div>
                <div class="form-group">
                    <label for="gerente_carr_${index}">Gerente:</label>
                    <input type="text" id="gerente_carr_${index}" name="carreras[${index}][gerente_carr]" placeholder="Gerente de carrera...">
                </div>
                <div class="form-group">
                    <label for="telefono_carr_${index}">Teléfono:</label>
                    <input type="text" id="telefono_carr_${index}" name="carreras[${index}][telefono_carr]" placeholder="Teléfono de carrera...">
                </div>
                <div class="form-group">
                    <label for="correo_carr_${index}">Correo:</label>
                    <input type="email" id="correo_carr_${index}" name="carreras[${index}][correo_carr]" placeholder="Correo de carrera...">
                    <span class="delete-carrera" onclick="eliminarCarrera(this)"><i class="fa-solid fa-trash"></i> Eliminar</span>
                </div>
            `;

            container.appendChild(newBlock);
        });

        // Función para eliminar carrera
        function eliminarCarrera(element) {
            const block = element.closest('.carrera-block');
            // Si es una carrera existente (tiene ID), marcamos para eliminación
            if (block.querySelector('input[name*="[id_carrera]"]')) {
                block.style.display = 'none';
                // Agregar campo oculto para indicar que se debe eliminar
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = block.querySelector('input[name*="[id_carrera]"]').name.replace('id_carrera',
                    '_destroy');
                hiddenInput.value = '1';
                block.appendChild(hiddenInput);
            } else {
                // Si es una carrera nueva, simplemente la removemos
                block.remove();
            }
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editForm = document.querySelector('form[action*="/instituciones/"]');

            if (editForm) {
                editForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const submitButton = editForm.querySelector('button[type="submit"]');
                    const originalText = submitButton.innerHTML;
                    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Actualizando...';
                    submitButton.disabled = true;

                    fetch(editForm.action, {
                            method: 'POST',
                            body: new FormData(editForm),
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                showConfirmationModal('success', data.message, 'fa-university');
                                // Redirigir después de 3 segundos
                                setTimeout(() => {
                                    window.location.href =
                                        "{{ route('instituciones.index') }}";
                                }, 3000);
                            } else {
                                showConfirmationModal('error', data.message);
                            }
                        })
                        .catch(error => {
                            showConfirmationModal('error',
                                'Error de conexión. Por favor intenta nuevamente.');
                        })
                        .finally(() => {
                            submitButton.innerHTML = originalText;
                            submitButton.disabled = false;
                        });
                });
            }

            @if (session('success'))
                showConfirmationModal('success', '{{ session('success') }}', 'fa-university');
            @endif

            @if (session('error'))
                showConfirmationModal('error', '{{ session('error') }}');
            @endif
        });
    </script>
    <script src="{{ asset('js/menu_modal.js') }}"></script>

</body>

</html>
