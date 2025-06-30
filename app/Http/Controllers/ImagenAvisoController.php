<?php

namespace App\Http\Controllers;

use App\Models\ImagenAviso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;


class ImagenAvisoController extends Controller
{
    public function index()
    {
        $imagenes = ImagenAviso::where('fecha_inicio', '<=', now())
            ->where('fecha_fin', '>=', now())
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
        DB::beginTransaction();

        try {
            // Validación adicional
            if (!$imagenAviso->exists) {
                throw new \Exception("El registro de imagen no existe en la base de datos");
            }

            $nombreArchivo = $imagenAviso->nombre_archivo;
            $rutaRelativa = 'imagenes_avisos/' . $nombreArchivo;
            $rutaAbsoluta = storage_path('app/public/' . $rutaRelativa);

            // Verificar y eliminar archivo físico
            if ($nombreArchivo && Storage::disk('public')->exists($rutaRelativa)) {
                if (!Storage::disk('public')->delete($rutaRelativa)) {
                    throw new \Exception("No se pudo eliminar el archivo físico");
                }
            }

            // Eliminar registro de la base de datos
            if (!$imagenAviso->delete()) {
                throw new \Exception("No se pudo eliminar el registro de la base de datos");
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Imagen eliminada correctamente',
                'deleted' => [
                    'id' => $imagenAviso->id,
                    'file' => $nombreArchivo
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Error eliminando imagen: ' . $e->getMessage());
            \Log::error('Datos imagen: ' . json_encode([
                'id' => $imagenAviso->id ?? null,
                'nombre_archivo' => $nombreArchivo ?? null,
                'ruta' => $imagenAviso->ruta ?? null
            ]));

            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la imagen: ' . $e->getMessage()
            ], 500);
        }
    }

    public function toggle(ImagenAviso $imagen)
    {
        $imagen->activo = !$imagen->activo;
        $imagen->save();

        return response()->json([
            'success' => true,
            'message' => 'Estado de imagen actualizado',
            'activo' => $imagen->activo
        ]);
    }
}