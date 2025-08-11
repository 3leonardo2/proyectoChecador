<?php

use Illuminate\Support\Facades\Route;
use App\Models\Practicante;
use Illuminate\Database\Eloquent\ModelNotFoundException;

Route::get('/practicante/{codigo}', function ($codigo) {
    try {
        $practicante = Practicante::with(['institucion', 'carrera'])
            ->where('codigo', $codigo)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'practicante' => [
                'nombre_completo' => $practicante->nombre . ' ' . $practicante->apellidos,
                'horas_requeridas' => $practicante->horas_requeridas,
                'horas_registradas' => $practicante->horas_registradas,
                'institucion' => $practicante->institucion->nombre ?? 'No especificada',
                'carrera' => $practicante->carrera->nombre_carr ?? 'No especificada',
                'fecha_inicio' => $practicante->fecha_inicio,
                'fecha_final' => $practicante->fecha_final,
                'area_asignada' => $practicante->area_asignada
            ],
            'registros' => \App\Models\Bitacora::where('practicante_id', $practicante->id_practicante)
                ->orderBy('fecha', 'desc')
                ->orderBy('hora', 'desc')
                ->limit(200)
                ->get()
        ]);
    } catch (ModelNotFoundException $e) {
        return response()->json([
            'success' => false,
            'error' => 'El código que ingresaste está incorrecto o no existe'
        ], 404);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => 'Error inesperado',
            'message' => $e->getMessage()
        ], 500);
    }
});
