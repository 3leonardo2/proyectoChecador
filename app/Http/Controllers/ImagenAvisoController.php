<?php

namespace App\Http\Controllers;

use App\Models\ImagenAviso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImagenAvisoController extends Controller
{
    public function index()
    {
        $imagenes = ImagenAviso::where('fecha_inicio', '<=', now())
            ->where('fecha_fin', '>=', now())
            ->where('activo', true)
            ->orderBy('fecha_inicio', 'asc')
            ->get();

        return response()->json($imagenes);
    }

    public function store(Request $request)
    {
        $request->validate([
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'duracion' => 'required|integer|min:1',
            'titulo' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        $file = $request->file('imagen');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('imagenes_avisos', $fileName, 'public');

        $imagen = ImagenAviso::create([
            'ruta' => '/storage/' . $filePath,
            'nombre_archivo' => $fileName,
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'duracion' => $request->duracion,
            'user_id' => auth()->id(),
        ]);

        return response()->json($imagen, 201);
    }

    public function update(Request $request, ImagenAviso $imagenAviso)
    {
        $request->validate([
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'duracion' => 'required|integer|min:1',
            'titulo' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        if ($request->hasFile('imagen')) {
            // Eliminar la imagen anterior
            Storage::disk('public')->delete(str_replace('/storage/', '', $imagenAviso->ruta));

            $file = $request->file('imagen');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('imagenes_avisos', $fileName, 'public');

            $imagenAviso->ruta = '/storage/' . $filePath;
            $imagenAviso->nombre_archivo = $fileName;
        }

        $imagenAviso->update([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'duracion' => $request->duracion,
        ]);

        return response()->json($imagenAviso);
    }

    public function destroy(ImagenAviso $imagenAviso)
    {
        // El nombre del parámetro debe coincidir con el resource en la ruta.
        // Si la ruta es /admin/imagenes-avisos/{imagenes_aviso}, el parámetro debe ser $imagenes_aviso.
        // Asumiré que el model binding funciona y el parámetro es $imagenAviso.

        Storage::disk('public')->delete(str_replace('/storage/', '', $imagenAviso->ruta));
        $imagenAviso->delete();

        // Devuelve una respuesta JSON en lugar de 204
        return response()->json(['success' => true, 'message' => 'Imagen eliminada correctamente.']);
    }
}