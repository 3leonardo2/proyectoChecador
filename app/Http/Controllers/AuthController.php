<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use App\Models\Administrador;
use App\Models\Practicante;

class AuthController extends Controller
{
    // Mostrar formulario de login
    public function showLoginForm()
    {
        return view('login');
    }

    // Mostrar formulario de registro
    public function showRegistrationForm()
    {
        return view('register');
    }

    // Procesar login
public function login(Request $request)
{
    $credentials = $request->validate([
        'correo' => 'required|email',
        'contrasena' => 'required'
    ]);
    
    // Normalizar el correo
    $correoNormalizado = strtolower($credentials['correo']);
    $credentials['correo'] = $correoNormalizado; // Actualizar el array de credenciales

    $admin = Administrador::where('correo', $correoNormalizado)->first();

    if ($admin && $admin->es_generico) {
        return back()->with(
            'generic_warning',
            'Está utilizando una cuenta genérica.'
        );
    }

    if (Auth::guard('admin')->attempt([
        'correo' => $correoNormalizado, // Usar el correo normalizado aquí
        'password' => $credentials['contrasena']
    ])) {
        $request->session()->regenerate();

        return Auth::guard('admin')->user()->rol === 'rh'
            ? redirect()->intended('/practicantes')
            : redirect()->intended('/asesor/practicantes');
    }

    return back()->withErrors(['correo' => 'Credenciales incorrectas']);
}

    public function index()
    {
        
        // Verifica si el usuario está autenticado
        $user = Auth::guard('admin')->user();

        // Verifica el rol directamente
        if (!$user || $user->rol !== 'asesor') {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        $departamento = $user->departamento;

        $practicantes = Practicante::where('area_asignada', $departamento)
            ->with('institucion')
            ->get();

        return view('asesor.lista_practicantes_asesor', compact('practicantes'));
    }


    // Procesar registro
    public function register(Request $request)
    {
        // Solo RH puede registrar nuevos administradores
        if (Auth::guard('admin')->user()->rol !== 'rh') {
            abort(403, 'No tienes permiso para esta acción');
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'correo' => 'required|email|unique:administradores,correo|max:255',
            'contrasena' => 'required|min:8|confirmed',
            'departamento' => 'required|string|max:100',
            'rol' => 'required|in:rh,asesor'
        ]);

        $admin = Administrador::create([
            'nombre' => $validated['nombre'],
            'correo' => $validated['correo'],
            'contrasena' => Hash::make($validated['contrasena']),
            'departamento' => $validated['departamento'],
            'rol' => $validated['rol']
        ]);

        return redirect()->route('practicantes.index')->with('success', 'Administrador registrado exitosamente');
    }

    // Cerrar sesión
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}