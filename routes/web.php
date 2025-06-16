<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PracticanteController;
use App\Http\Controllers\InstitucionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
});

// Auth
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');

// Practicantes
Route::get('/practicantes', [PracticanteController::class, 'index'])->name('practicantes.index');
Route::get('/practicantes/{id}', [PracticanteController::class, 'show'])->name('practicantes.show');
Route::get('practicantes/{id}/edit', [PracticanteController::class, 'edit'])->name('practicantes.edit');
Route::put('practicantes/{id}', [PracticanteController::class, 'update'])->name('practicantes.update');
Route::get('/registrar_prac', [PracticanteController::class, 'create'])->name('practicantes.create');
Route::post('/registrar_prac', [PracticanteController::class, 'store'])->name('practicantes.store');
Route::get('/practicantes/get-by-carrera', [PracticanteController::class, 'getByCarrera'])->name('practicantes.getByCarrera');

// Instituciones
Route::get('/registrar_insti', [InstitucionController::class, 'create'])->name('instituciones.create');
Route::post('/registrar_insti', [InstitucionController::class, 'store'])->name('instituciones.store');

// Otras vistas
Route::get('/edit_prac', function () {
    return view('edit_prac');
});
Route::get('/bitacora', function () {
    return view('bitacora');
});
Route::get('/consulta_horas', function () {
    return view('consulta_horas');
});
Route::get('/revisar_practicante', function () {
    return view('revisar_practicante');
});
Route::get('/lista_revisiones', function () {
    return view('lista_revisiones');
});
Route::get('/modificar_avisos', function () {
    return view('modificar_avisos');
});
