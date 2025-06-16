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
        <a href="#" class="back-button">
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
                <p class="practicante-name">Leonardo Alatorre Esparza</p>
                <p class="practicante-area">Sistemas</p>
            </div>

            <div class="review-form-section">
                <div class="form-group">
                    <label for="nombreRevision">Nombre de quién hará la revisión:</label>
                    <input type="text" id="nombreRevision" placeholder="Escriba su nombre...">
                </div>

                <div class="form-group">
                    <label for="nombreRevision">Nombre de revisión:</label>
                    <input type="text" id="nombreRevision" placeholder="Escriba el nombre/tipo de su revisión...">
                </div>

                <div class="form-group">
                    <label for="descripcionRevision">Agregue una descripción:</label>
                    <textarea id="descripcionRevision" placeholder="Haga click aquí para empezar a redactar su revisión..."></textarea>
                </div>

                <div class="form-group">
                    <label>Evaluación general (opcional):</label>
                    <div class="stars-rating" data-rating="0">
                        <span class="star" data-value="1"><i class="fa-solid fa-star"></i></span>
                        <span class="star" data-value="2"><i class="fa-solid fa-star"></i></span>
                        <span class="star" data-value="3"><i class="fa-solid fa-star"></i></span>
                        <span class="star" data-value="4"><i class="fa-solid fa-star"></i></span>
                        <span class="star" data-value="5"><i class="fa-solid fa-star"></i></span>
                    </div>
                </div>

                <div class="form-actions">
                    <button class="add-review-button">Agregar revisión</button>
                    <button class="cancel-review-button">Cancelar revisión</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const starsContainer = document.querySelector('.stars-rating');
            const stars = starsContainer.querySelectorAll('.star');

            stars.forEach(star => {
                star.addEventListener('click', function() {
                    const value = parseInt(this.dataset.value);
                    starsContainer.dataset.rating = value; // Actualiza el rating en el contenedor
                    highlightStars(value);
                });

                star.addEventListener('mouseover', function() {
                    const value = parseInt(this.dataset.value);
                    highlightStars(value, true); // Resalta al pasar el mouse
                });

                star.addEventListener('mouseout', function() {
                    const currentRating = parseInt(starsContainer.dataset.rating);
                    highlightStars(currentRating); // Vuelve al rating seleccionado
                });
            });

            // Función para resaltar estrellas
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

            // Inicializar las estrellas con el valor por defecto (0 o el que tengas en data-rating)
            highlightStars(parseInt(starsContainer.dataset.rating));
        });
    </script>
</body>
</html>