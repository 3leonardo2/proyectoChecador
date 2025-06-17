<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revisión de Practicante</title>
    <link rel="stylesheet" href="{{ asset('css/revisar_practicante.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <div class="header">
        <a href="{{ route('evaluaciones.index', $practicante->id_practicante) }}" class="back-button">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1>Revisión de practicante</h1>
    </div>

    <div class="main-content-wrapper">
        <div class="review-card">
            <div class="practicante-info-sidebar">
                <div class="practicante-avatar">
                    <i class="fa-solid fa-user"></i>
                </div>
                <p class="practicante-name">{{ $practicante->nombre }} {{ $practicante->apellidos }}</p>
                <p class="practicante-area">{{ $practicante->area_asignada }}</p>
            </div>

            <div class="review-form-section">
                <form action="{{ route('evaluaciones.store', $practicante->id_practicante) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="nombre_asesor">Nombre de quién hará la revisión:</label>
                        <input type="text" id="nombre_asesor" name="nombre_asesor" placeholder="Escriba su nombre..."
                            required>
                    </div>

                    <div class="form-group">
                        <label for="nombre_revision">Nombre de revisión:</label>
                        <input type="text" id="nombre_revision" name="nombre_revision"
                            placeholder="Escriba el nombre/tipo de su revisión..." required>
                    </div>

                    <div class="form-group">
                        <label for="descripcion_revision">Agregue una descripción:</label>
                        <textarea id="descripcion_revision" name="descripcion_revision"
                            placeholder="Haga click aquí para empezar a redactar su revisión..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label>Evaluación general (opcional):</label>
                        <div class="stars-rating" data-rating="0">
                            <input type="hidden" id="evaluacion_gral" name="evaluacion_gral" value="0">
                            <span class="star" data-value="1"><i class="fa-solid fa-star"></i></span>
                            <span class="star" data-value="2"><i class="fa-solid fa-star"></i></span>
                            <span class="star" data-value="3"><i class="fa-solid fa-star"></i></span>
                            <span class="star" data-value="4"><i class="fa-solid fa-star"></i></span>
                            <span class="star" data-value="5"><i class="fa-solid fa-star"></i></span>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="add-review-button">Agregar revisión</button>
                        <a href="{{ route('evaluaciones.index', $practicante->id_practicante) }}"
                            class="cancel-review-button">Cancelar revisión</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const starsContainer = document.querySelector('.stars-rating');
            const stars = starsContainer.querySelectorAll('.star');
            const hiddenInput = document.getElementById('evaluacion_gral');

            stars.forEach(star => {
                star.addEventListener('click', function() {
                    const value = parseInt(this.dataset.value);
                    starsContainer.dataset.rating = value;
                    hiddenInput.value = value;
                    highlightStars(value);
                });

                star.addEventListener('mouseover', function() {
                    const value = parseInt(this.dataset.value);
                    highlightStars(value, true);
                });

                star.addEventListener('mouseout', function() {
                    const currentRating = parseInt(starsContainer.dataset.rating);
                    highlightStars(currentRating);
                });
            });

            function highlightStars(ratingValue, isHover = false) {
                stars.forEach(star => {
                    const starValue = parseInt(star.dataset.value);
                    if (starValue <= ratingValue) {
                        star.querySelector('i').classList.add('selected');
                        star.querySelector('i').classList.remove('unselected');
                    } else {
                        star.querySelector('i').classList.remove('selected');
                        star.querySelector('i').classList.add('unselected');
                    }
                });
            }

            highlightStars(parseInt(starsContainer.dataset.rating));
        });
    </script>
</body>

</html>
