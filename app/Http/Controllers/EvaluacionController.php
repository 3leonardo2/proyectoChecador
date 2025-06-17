<?php

namespace App\Http\Controllers;

use App\Models\Evaluacion;
use App\Models\Practicante;
use Illuminate\Http\Request;

class EvaluacionController extends Controller
{
    public function index($id_practicante)
    {
        $practicante = Practicante::with('evaluaciones')->findOrFail($id_practicante);
        return view('lista_revisiones', compact('practicante'));
    }


    public function create($id_practicante)
    {
        $practicante = Practicante::findOrFail($id_practicante);
        return view('revisar_practicante', compact('practicante'));
    }


    public function store(Request $request, $id_practicante)
    {
        $validated = $request->validate([
            'nombre_asesor' => 'required|string|max:255',
            'nombre_revision' => 'required|string|max:255',
            'descripcion_revision' => 'required|string',
            'evaluacion_gral' => 'nullable|integer|min:0|max:5',
        ]);

        Evaluacion::create([
            'nombre_asesor' => $validated['nombre_asesor'],
            'id_practicante' => $id_practicante,
            'nombre_revision' => $validated['nombre_revision'],
            'descripcion_revision' => $validated['descripcion_revision'],
            'evaluacion_gral' => $validated['evaluacion_gral'] ?? 0,
        ]);

        return redirect()->route('evaluaciones.index', $id_practicante)
            ->with('success', 'Evaluación agregada correctamente.');
    }
}