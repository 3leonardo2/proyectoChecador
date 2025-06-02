<?php
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
});


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');

Route::get('/login', function () {
    return view('login');
});

Route::get('/edit_prac', function () {
    return view('edit_prac');
});

Route::get('/detallesprac', function () {
    return view('detallesprac');
})->name('practicantes.detalles');

Route::get('/bitacora', function () {
    return view('bitacora');
});

Route::get('/lista_practicantes', function () {
    return view('lista_practicantes');
});

Route::get('/registrar_prac', function () {
    return view('registrar_prac');
});

Route::get('/registrar_insti', function () {
    return view('registrar_insti');
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