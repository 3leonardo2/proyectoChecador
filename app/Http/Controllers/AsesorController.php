<?php

namespace App\Http\Controllers;

use App\Models\Practicante;
use App\Models\Administrador;
use App\Models\Evaluacion;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AsesorController extends Controller
{
    public function index()
    {
        $departamento = Auth::guard('admin')->user()->departamento;
        $practicantes = Practicante::where('area_asignada', $departamento)
            ->with('institucion')
            ->get();

        return view('asesor.lista_practicantes_asesor', compact('practicantes'));
    }

    public function create()
    {
        $departamentos = [
            'Recursos Humanos',
            'Sistemas',
            'Administración',
            'Dirección',
            'Cocina'
        ];

        return view('registrar_admin', compact('departamentos'));
    }

    public function store(Request $request)
    {
        \Log::info('Iniciando registro de administrador', ['request' => $request->all()]);

        try {
            // Validación común para ambos tipos
            $validated = $request->validate([
                'nombre' => 'required|string|max:255',
                'departamento' => 'required|string|max:255',
                'rol' => 'required|in:rh,asesor',
                'tipo_registro' => 'required|in:manual,automatico'
            ]);

            // Validación condicional para registro manual
            if ($validated['tipo_registro'] === 'manual') {
                $request->validate([
                    'correo' => 'required|email|unique:administradores,correo',
                    'contrasena' => 'required|string|min:8|confirmed'
                ]);
                $correo = $request->correo;
                $contrasena = $request->contrasena;
            } else {
                // Generar credenciales automáticas
                $correo = strtolower(str_replace(' ', '', $validated['departamento'])) . '_asesor@empresa.com';
                $contrasena = Str::random(12);
            }

            // Crear el administrador
            $admin = Administrador::create([
                'nombre' => $validated['nombre'],
                'correo' => $correo,
                'contrasena' => Hash::make($contrasena),
                'departamento' => $validated['departamento'],
                'rol' => $validated['rol'],
                'es_generico' => ($validated['tipo_registro'] === 'automatico')
            ]);

            // Preparar mensaje de éxito
            $message = $validated['tipo_registro'] === 'automatico'
                ? 'Administrador genérico creado correctamente.'
                : 'Administrador registrado correctamente.';

            // Preparar datos para mostrar
            $response = redirect()->route('admin.create')->with('success', $message);

            if ($validated['tipo_registro'] === 'automatico') {
                $response->with('credenciales', [
                    'correo' => $correo,
                    'contrasena' => $contrasena
                ]);
            }

            return $response;

        } catch (\Exception $e) {
            \Log::error('Error en registro: ' . $e->getMessage());
            return redirect()->route('admin.create')
                ->with('error', 'Error al registrar: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        $practicante = Practicante::with('institucion')->findOrFail($id);
        return view('asesor.detallesprac_asesor', compact('practicante'));
    }

    public function detalles_practicante($id)
    {
        $departamento = Auth::guard('admin')->user()->departamento;
        $practicante = Practicante::where('area_asignada', $departamento)
            ->with(['institucion', 'carrera'])
            ->findOrFail($id);

        return view('asesor.detallesprac_asesor', compact('practicante'));
    }

    public function evaluaciones_practicante($id)
    {
        $departamento = Auth::guard('admin')->user()->departamento;
        $practicante = Practicante::where('area_asignada', $departamento)
            ->with(['institucion', 'carrera', 'evaluaciones'])
            ->findOrFail($id);

        return view('asesor.lista_revisiones_asesor', compact('practicante'));
    }

    public function crear_evaluacion($id)
    {
        $departamento = Auth::guard('admin')->user()->departamento;
        $asesor = Auth::guard('admin')->user();

        $practicante = Practicante::where('area_asignada', $departamento)
            ->findOrFail($id);

        return view('asesor.revisar_practicante_asesor', compact('practicante', 'asesor'));
    }

    public function store_evaluacion(Request $request, $id)
    {
        $departamento = Auth::guard('admin')->user()->departamento;

        // Verificar que el practicante pertenece al departamento del asesor
        $practicante = Practicante::where('area_asignada', $departamento)
            ->findOrFail($id);

        $validated = $request->validate([
            'nombre_asesor' => 'required|string|max:255',
            'nombre_revision' => 'required|string|max:255',
            'descripcion_revision' => 'required|string',
            'evaluacion_gral' => 'nullable|integer|min:0|max:5',
        ]);

        Evaluacion::create([
            'id_practicante' => $practicante->id_practicante,
            'nombre_asesor' => $validated['nombre_asesor'],
            'nombre_revision' => $validated['nombre_revision'],
            'descripcion_revision' => $validated['descripcion_revision'],
            'evaluacion_gral' => $validated['evaluacion_gral'] ?? 0,
            'fecha_evaluacion' => now(),
        ]);

        return redirect()->route('asesor.practicantes.evaluaciones', $practicante->id_practicante)
            ->with('success', 'Evaluación creada correctamente.');
    }

    // Listar administradores con filtros
    public function listaAdministradores(Request $request)
    {
        $query = Administrador::query();

        // Filtros por departamento y rol
        if ($request->filled('departamento')) {
            $query->where('departamento', $request->departamento);
        }
        if ($request->filled('rol')) {
            $query->where('rol', $request->rol);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'ILIKE', "%$search%")
                    ->orWhere('correo', 'ILIKE', "%$search%");
            });
        }

        $administradores = $query->orderBy('nombre')->get();

        return view('lista_administradores', compact('administradores'));
    }

    // Mostrar formulario de edición
    public function edit($id)
    {
        $admin = Administrador::findOrFail($id);
        return view('editar_administrador', compact('admin'));
    }

    // Guardar cambios de edición
    public function update(Request $request, $id)
    {
        $admin = Administrador::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'required|email|unique:administradores,correo,' . $admin->id_admin . ',id_admin',
            'departamento' => 'required|string|max:255',
            'rol' => 'required|in:rh,asesor',
            'contrasena' => 'nullable|string|min:8|confirmed',
        ]);

        $admin->nombre = $validated['nombre'];
        $admin->correo = $validated['correo'];
        $admin->departamento = $validated['departamento'];
        $admin->rol = $validated['rol'];

        if (!empty($validated['contrasena'])) {
            $admin->contrasena = Hash::make($validated['contrasena']);
        }

        $admin->save();

        return redirect()->route('administradores.lista')->with('success', 'Administrador actualizado correctamente.');
    }
}