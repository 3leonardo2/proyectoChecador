<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PracticanteController;
use App\Http\Controllers\InstitucionController;
use App\Http\Controllers\EvaluacionController;
use App\Http\Controllers\BitacoraController;
use App\Http\Controllers\AvisoController;
use App\Http\Controllers\ImagenAvisoController;
use App\Http\Controllers\AsesorController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/bitacora', [BitacoraController::class, 'index'])->name('bitacora.index');

// Rutas para bitácora
Route::prefix('bitacora')->group(function () {
    Route::get('/', [BitacoraController::class, 'index'])->name('bitacora.index');
    Route::post('/registrar', [BitacoraController::class, 'registrarEvento'])->name('bitacora.registrar');
    Route::post('/registrar-automático', [BitacoraController::class, 'registrarEventoAutomatico'])->name('bitacora.registrar.automatico');
});

// Ruta para consulta de horas
Route::get('/consulta_horas', function () {
    return view('consulta_horas');
})->name('consulta_horas');

// Rutas de autenticación
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->name('logout');
});

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
    Route::get('/{id_institucion}/carreras', [InstitucionController::class, 'getCarreras'])->name('instituciones.carreras');
});

//Rutas para avisos
Route::get('/modificar_avisos', [AvisoController::class, 'index'])->name('modificar_avisos.index');
Route::resource('avisos', AvisoController::class)->except(['create', 'edit', 'show']);
Route::prefix('admin/imagenes-avisos')->group(function () {
    Route::get('/', [ImagenAvisoController::class, 'index'])->name('imagenes-avisos.index');
    Route::post('/', [ImagenAvisoController::class, 'store'])->name('imagenes-avisos.store');
    Route::put('/{imagenAviso}', [ImagenAvisoController::class, 'update'])->name('imagenes-avisos.update');
    Route::delete('/{imagenAviso}', [ImagenAvisoController::class, 'destroy'])->name('imagenes-avisos.destroy');
    Route::post('/{imagen}/toggle', [ImagenAvisoController::class, 'toggle'])->name('imagenes-avisos.toggle');
});

//Rutas para generación de pdf´s
Route::get('/practicantes/{practicante}/credencial', [PracticanteController::class, 'generarCredencial'])
    ->name('practicantes.credencial');
Route::get('/practicantes/{practicante}/reporte', [PracticanteController::class, 'generarReporte'])
    ->name('practicantes.reporte');


Route::get('/admin/registrar', [AsesorController::class, 'create'])->name('admin.create');
Route::post('/admin/registrar', [AsesorController::class, 'store'])->name('admin.store');
Route::get('/administradores/{id}/editar', [AsesorController::class, 'edit'])->name('administradores.edit');
Route::get('/administradores', [AsesorController::class, 'listaAdministradores'])->name('administradores.lista');
Route::get('/administradores/{id}/editar', [AsesorController::class, 'edit'])->name('administradores.edit');
Route::put('/administradores/{id}', [AsesorController::class, 'update'])->name('administradores.update');

Route::get('/asesor/practicantes/{id}', [AsesorController::class, 'show'])->name('asesor.practicantes.show');

// Rutas para asesores
Route::prefix('asesor')->group(function () {
    Route::get('/practicantes', [AsesorController::class, 'index'])->name('asesor.practicantes.index');
    Route::get('/practicantes/{id}', [AsesorController::class, 'detalles_practicante'])->name('asesor.practicantes.show');
    
    // Rutas para evaluaciones
    Route::prefix('/practicantes/{id}')->group(function () {
        Route::get('/evaluaciones', [AsesorController::class, 'evaluaciones_practicante'])->name('asesor.practicantes.evaluaciones');
        Route::get('/evaluaciones/crear', [AsesorController::class, 'crear_evaluacion'])->name('asesor.practicantes.evaluaciones.create');
        Route::post('/evaluaciones', [AsesorController::class, 'store_evaluacion'])->name('asesor.practicantes.evaluaciones.store');
    });
});
