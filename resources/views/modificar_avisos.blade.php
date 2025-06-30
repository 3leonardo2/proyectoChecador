<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificación de Avisos</title>
    <link rel="stylesheet" href="{{ asset('css/modificar_avisos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/menu_modal.css') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <div class="header">
        <a href="#" class="back-button">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1>Modificación de avisos</h1>
        <button class="menu-button" id="menuButton">
            <i class="fa-solid fa-bars"></i>
        </button>
    </div>
    @include('partials.menu_modal')

    <div class="main-content-wrapper">
        <div class="avisos-card">
            <div class="sidebar-tabs">
                <div class="tab-item active" data-tab="modificar-avisos">Modificar Avisos</div>
                <div class="tab-item" data-tab="anadir-imagenes">Añadir imágenes</div>
                <div class="tab-item" data-tab="cumpleanos">Cumpleaños</div>
            </div>

            <div class="tab-content-container">
                <div class="tab-content active" id="modificar-avisos">
                    <h2>Avisos Actuales</h2>
                    <button class="add-aviso-button">Añadir aviso</button>

                    @include('partials.aviso_modal')

                    @foreach ($avisos as $aviso)
                        <div class="aviso-item" data-aviso-id="{{ $aviso->id }}"
                            data-fecha-inicio="{{ $aviso->fecha_inicio->format('Y-m-d\TH:i') }}"
                            data-fecha-fin="{{ $aviso->fecha_fin->format('Y-m-d\TH:i') }}">
                            <p>- {{ $aviso->contenido }}</p>
                            <div class="aviso-actions">
                                <button class="edit-aviso"><i class="fa-solid fa-pencil"></i></button>
                                <button class="delete-aviso"><i class="fa-solid fa-trash-can"></i></button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="tab-content" id="anadir-imagenes">
                    <h2>Gestión de Imágenes</h2>
                    <p>Aquí podrás subir y gestionar las imágenes de avisos o banners.</p>

                    <div class="current-images-grid">
                        @foreach ($imagenes as $imagen)
                            <div class="image-item" data-image-id="{{ $imagen->id }}"
                                data-titulo="{{ $imagen->titulo }}" data-descripcion="{{ $imagen->descripcion }}"
                                data-fecha-inicio="{{ $imagen->fecha_inicio->format('Y-m-d\TH:i') }}"
                                data-fecha-fin="{{ $imagen->fecha_fin->format('Y-m-d\TH:i') }}"
                                data-duracion="{{ $imagen->duracion }}" data-activo="{{ $imagen->activo }}">
                                <img src="{{ $imagen->ruta }}" alt="{{ $imagen->titulo ?? 'Imagen de aviso' }}">
                                <div class="image-status {{ $imagen->activo ? 'active' : 'inactive' }}">
                                    {{ $imagen->activo ? 'Activa' : 'Inactiva' }}
                                </div>
                                <div class="image-dates">
                                    <small>Inicio: {{ $imagen->fecha_inicio->format('d/m/Y H:i') }}</small>
                                    <small>Fin: {{ $imagen->fecha_fin->format('d/m/Y H:i') }}</small>
                                </div>
                                <div class="image-actions">
                                    <button class="edit-image-button"><i class="fa-solid fa-pencil"></i></button>
                                    <button class="delete-image-button"><i class="fa-solid fa-trash-can"></i></button>
                                    <button class="toggle-image-button" data-id="{{ $imagen->id }}">
                                        <i class="fa-solid fa-power-off"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <button class="add-new-image-button">Añadir imagen</button>

                    @include('partials.image_config_modal')

                </div>
                <div class="tab-content" id="cumpleanos">
                    <h2>Próximos Cumpleaños</h2>
                    <p>Consulta y gestiona los cumpleaños de los practicantes.</p>
                    <div class="cumpleanos-list">
                        <div class="cumpleanos-item">
                            <span class="cumpleanos-name">Juan Pérez</span>
                            <span class="cumpleanos-date">15 de Junio</span>
                        </div>
                        <div class="cumpleanos-item">
                            <span class="cumpleanos-name">Ana García</span>
                            <span class="cumpleanos-date">22 de Junio</span>
                        </div>
                    </div>
                    <button class="export-cumpleanos-button">Exportar Lista</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabItems = document.querySelectorAll('.tab-item');
            const tabContents = document.querySelectorAll('.tab-content');

            tabItems.forEach(item => {
                item.addEventListener('click', function() {
                    // Remover 'active' de todos los items y contenidos
                    tabItems.forEach(i => i.classList.remove('active'));
                    tabContents.forEach(c => c.classList.remove('active'));

                    // Añadir 'active' al item clickeado
                    this.classList.add('active');

                    // Mostrar el contenido correspondiente
                    const targetTabId = this.dataset.tab;
                    document.getElementById(targetTabId).classList.add('active');
                });
            });

            // JavaScript para previsualización de imágenes (solo para la sección "Añadir imágenes")
            const imageUpload = document.getElementById('imageUpload');
            const imagePreview = document.getElementById('imagePreview');

            if (imageUpload && imagePreview) {
                imageUpload.addEventListener('change', function() {
                    imagePreview.innerHTML = ''; // Limpiar previsualizaciones anteriores

                    if (this.files && this.files.length > 0) {
                        Array.from(this.files).forEach(file => {
                            if (file.type.startsWith('image/')) {
                                const reader = new FileReader();
                                reader.onload = function(e) {
                                    const imgContainer = document.createElement('div');
                                    imgContainer.classList.add('image-preview-item');
                                    const img = document.createElement('img');
                                    img.src = e.target.result;
                                    img.alt = file.name;
                                    imgContainer.appendChild(img);
                                    imagePreview.appendChild(imgContainer);
                                };
                                reader.readAsDataURL(file);
                            }
                        });
                    }
                });
            }
        });
    </script>

    <script src="{{ asset('js/menu_modal.js') }}"></script>
    <script src="{{ asset('js/avisoModalHandler.js') }}"></script>
    <script src="{{ asset('js/imageModalHandler.js') }}"></script>
</body>

</html>
