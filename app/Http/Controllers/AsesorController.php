<?php

namespace App\Http\Controllers;

use App\Models\Practicante;
use App\Models\Administrador;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
        return view('registrar_admin');
    }

    public function store(\Illuminate\Http\Request $request)
    {
        // Validar los datos
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'required|email|unique:administradores,correo',
            'contrasena' => 'required|string|min:8|confirmed',
            'departamento' => 'required|string|max:255',
            'rol' => 'required|in:rh,asesor',
        ]);

        // Crear el nuevo administrador
        \App\Models\Administrador::create([
            'nombre' => $validated['nombre'],
            'correo' => $validated['correo'],
            'contrasena' => \Illuminate\Support\Facades\Hash::make($validated['contrasena']),
            'departamento' => $validated['departamento'],
            'rol' => $validated['rol'],
        ]);

        return redirect()->route('administradores.create')->with('success', 'Administrador registrado correctamente.');
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
            ->with('evaluaciones')
            ->findOrFail($id);

        return view('asesor.lista_revisiones_asesor', compact('practicante'));
    }

    public function crear_evaluacion($id)
    {
        $departamento = Auth::guard('admin')->user()->departamento;
        $practicante = Practicante::where('area_asignada', $departamento)
            ->findOrFail($id);

        return view('asesor.revisar_practicante_asesor', compact('practicante'));
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
            $query->where(function($q) use ($search) {
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