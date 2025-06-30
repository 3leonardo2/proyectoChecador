<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PracticanteController;
use App\Http\Controllers\InstitucionController;
use App\Http\Controllers\EvaluacionController;
use App\Http\Controllers\BitacoraController;
use App\Http\Controllers\AvisoController;
use App\Http\Controllers\ImagenAvisoController;

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
    Route::get('/', [InstitucionController::class, 'index'])->name('instituciones.index');
    Route::get('/registrar_insti', [InstitucionController::class, 'create'])->name('instituciones.create');
    Route::post('/registrar_insti', [InstitucionController::class, 'store'])->name('instituciones.store');
    Route::get('/{id_institucion}/edit', [InstitucionController::class, 'edit'])->name('instituciones.edit');
    Route::put('/{id_institucion}', [InstitucionController::class, 'update'])->name('instituciones.update');
});

// Rutas para bitácora
Route::prefix('bitacora')->group(function () {
    Route::get('/', [BitacoraController::class, 'index'])->name('bitacora.index');
    Route::post('/registrar', [BitacoraController::class, 'registrarEvento'])->name('bitacora.registrar');
});

// Ruta para consulta de horas
Route::get('/consulta_horas', function () {
    return view('consulta_horas');
})->name('consulta_horas');


Route::get('/modificar_avisos', [AvisoController::class, 'index'])->name('modificar_avisos.index');

Route::resource('avisos', AvisoController::class)->except(['create', 'edit', 'show']);

Route::prefix('admin/imagenes-avisos')->group(function () {
    Route::get('/', [ImagenAvisoController::class, 'index'])->name('imagenes-avisos.index');
    Route::post('/', [ImagenAvisoController::class, 'store'])->name('imagenes-avisos.store');
    Route::put('/{imagenAviso}', [ImagenAvisoController::class, 'update'])->name('imagenes-avisos.update');
    Route::delete('/{imagenAviso}', [ImagenAvisoController::class, 'destroy'])->name('imagenes-avisos.destroy');
    Route::post('/{imagen}/toggle', [ImagenAvisoController::class, 'toggle'])->name('imagenes-avisos.toggle');
});