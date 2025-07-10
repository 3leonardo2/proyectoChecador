<!DOCTYPE html>
<html lang="es">

<head>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Revisiones de Practicante</title>
    <link rel="stylesheet" href="{{ asset('css/lista_revisiones.css') }}">
    <link rel="stylesheet" href="{{ asset('css/menu_modal.css') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <div class="header">
        <a href="{{ route('asesor.practicantes.show', $practicante->id_practicante) }}" class="back-button">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1>Lista de revisiones</h1>
    </div>
    @include('partials.menu_modal')

    <div class="main-content-wrapper">
        <div class="review-list-card">
            <div class="practicante-info-sidebar">
                <div class="practicante-avatar">
                    @if ($practicante->profile_image && Storage::disk('public')->exists($practicante->profile_image))
                        <img src="{{ asset('storage/' . $practicante->profile_image) }}" alt="Foto del practicante"
                            class="profile-image">
                    @else
                        <div class="default-avatar">
                            <i class="fas fa-user-circle"></i>
                        </div>
                    @endif
                </div>
                <p class="practicante-name">{{ $practicante->nombre }} {{ $practicante->apellidos }}</p>
                <p class="practicante-area">{{ $practicante->area_asignada }}</p>
                <a href="{{ route('asesor.practicantes.evaluaciones.create', $practicante->id_practicante) }}"
                    class="add-review-button">Añadir revisión</a>
            </div>

            <div class="reviews-table-section">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Nº</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Evaluación general</th>
                                <th>Nombre Evaluador</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($practicante->evaluaciones as $index => $evaluacion)
                                <tr class="{{ $index % 2 === 0 ? 'highlighted-row' : '' }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $evaluacion->nombre_revision ?? 'Sin nombre' }}</td>
                                    <td>{{ $evaluacion->descripcion_revision ?? 'Sin descripción' }}</td>
                                    <td class="rating-cell">
                                        <span>{{ $evaluacion->evaluacion_gral }}/5</span>
                                        <div class="mini-stars">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i
                                                    class="fa-solid fa-star {{ $i <= $evaluacion->evaluacion_gral ? 'selected' : 'unselected' }}"></i>
                                            @endfor
                                        </div>
                                    </td>
                                    <td>{{ $evaluacion->nombre_asesor }}</td>
                                    <td>{{ \Carbon\Carbon::parse($evaluacion->fecha_evaluacion)->format('d/m/Y') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No hay evaluaciones registradas</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/menu_modal.js') }}"></script>
</body>

</html>
