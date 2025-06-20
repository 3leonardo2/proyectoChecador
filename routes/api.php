<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Practicante;
use App\Models\Bitacora;

Route::get('/practicante/{codigo}', function ($codigo) {
    try {
        $practicante = Practicante::with(['institucion', 'carrera'])
                    ->where('codigo', $codigo)
                    ->firstOrFail();
        
        return response()->json([
            'success' => true,
            'practicante' => [
                'nombre_completo' => $practicante->nombre . ' ' . $practicante->apellidos,
                'horas_requeridas' => $practicante->horas_requeridas, // Cambiado de horas_totales
                'horas_registradas' => $practicante->horas_registradas,
                'institucion' => $practicante->institucion->nombre ?? 'No especificada',
                'carrera' => $practicante->carrera->nombre_carr ?? 'No especificada',
                'fecha_inicio' => $practicante->fecha_inicio,
                'fecha_final' => $practicante->fecha_final,
                'area_asignada' => $practicante->area_asignada // Campo adicional
            ],
            'registros' => Bitacora::where('clave_prac', $codigo)
                          ->orderBy('fecha', 'desc')
                          ->orderBy('hora', 'desc')
                          ->limit(50)
                          ->get()
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => 'Practicante no encontrado',
            'message' => $e->getMessage()
        ], 404);
    }
});