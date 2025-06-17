<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PracticanteController;
use App\Http\Controllers\InstitucionController;
use App\Http\Controllers\EvaluacionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
});

// Rutas de autenticación
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');

// Rutas de practicantes
Route::prefix('practicantes')->group(function () {
    Route::get('/', [PracticanteController::class, 'index'])->name('practicantes.index');
    Route::get('/registrar_prac', [PracticanteController::class, 'create'])->name('practicantes.create');
    Route::post('/registrar_prac', [PracticanteController::class, 'store'])->name('practicantes.store');
    Route::get('/get-by-carrera', [PracticanteController::class, 'getByCarrera'])->name('practicantes.getByCarrera');
    
    // Rutas específicas de un practicante
    Route::prefix('{id_practicante}')->group(function () {
        Route::get('/', [PracticanteController::class, 'show'])->name('practicantes.show');
        Route::get('/edit', [PracticanteController::class, 'edit'])->name('practicantes.edit');
        Route::put('/', [PracticanteController::class, 'update'])->name('practicantes.update');
        
        // Rutas de evaluaciones para el practicante
        Route::prefix('evaluaciones')->group(function () {
            Route::get('/', [EvaluacionController::class, 'index'])->name('evaluaciones.index');
            Route::get('/create', [EvaluacionController::class, 'create'])->name('evaluaciones.create');
            Route::post('/', [EvaluacionController::class, 'store'])->name('evaluaciones.store');
        });
    });
});

// Rutas de instituciones
Route::prefix('instituciones')->group(function () {
    Route::get('/registrar_insti', [InstitucionController::class, 'create'])->name('instituciones.create');
    Route::post('/registrar_insti', [InstitucionController::class, 'store'])->name('instituciones.store');
});
// Rutas para instituciones
Route::get('/instituciones', [InstitucionController::class, 'index'])->name('instituciones.index');
Route::get('/instituciones/{id_institucion}/edit', [InstitucionController::class, 'edit'])->name('instituciones.edit');
Route::put('/instituciones/{id_institucion}', [InstitucionController::class, 'update'])->name('instituciones.update');

// Otras rutas de vistas
Route::get('/bitacora', function () {
    return view('bitacora');
})->name('bitacora');

Route::get('/consulta_horas', function () {
    return view('consulta_horas');
})->name('consulta_horas');

Route::get('/modificar_avisos', function () {
    return view('modificar_avisos');
})->name('modificar_avisos');

// Elimina estas rutas ya que están siendo manejadas por los controladores
// Route::get('/revisar_practicante', function () {
//     return view('revisar_practicante');
// });
// 
// Route::get('/lista_revisiones', function () {
//     return view('lista_revisiones');
// });
// 
// Route::get('/edit_prac', function () {
//     return view('edit_prac');
// });