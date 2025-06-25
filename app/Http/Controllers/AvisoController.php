<?php

namespace App\Http\Controllers;

use App\Models\Aviso;
use App\Models\ImagenAviso;
use Illuminate\Http\Request; // Asegúrate de importar Request

class AvisoController extends Controller
{
    public function index()
    {        $avisos = Aviso::where('fecha_fin', '>=', now())
                    ->orderBy('fecha_inicio', 'asc')
                    ->get();

        $imagenes = ImagenAviso::where('fecha_fin', '>=', now())
                    ->orderBy('fecha_inicio', 'asc')
                    ->get();


        return view('modificar_avisos', compact('avisos', 'imagenes'));
    }

    /**
     * Almacena un nuevo aviso en la base de datos.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'contenido' => 'required|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        Aviso::create($validated);

        return response()->json(['success' => true, 'message' => 'Aviso creado correctamente.']);
    }

    /**
     * Actualiza un aviso existente.
     */
    public function update(Request $request, Aviso $aviso)
    {
        $validated = $request->validate([
            'contenido' => 'required|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        $aviso->update($validated);

        return response()->json(['success' => true, 'message' => 'Aviso actualizado correctamente.']);
    }

    /**
     * Elimina un aviso.
     */
    public function destroy(Aviso $aviso)
    {
        $aviso->delete();

        return response()->json(['success' => true, 'message' => 'Aviso eliminado correctamente.']);
    }
}