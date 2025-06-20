<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use App\Models\Practicante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BitacoraController extends Controller
{
    public function index()
    {
        return view('bitacora');
    }

    public function registrarEvento(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|exists:practicantes,codigo',
            'tipo' => 'required|in:entrada,entrada_comedor,salida_comedor,salida'
        ]);

        $codigo = $request->codigo;
        $tipo = $request->tipo;
        $fechaHoy = Carbon::today()->toDateString();
        $practicante = Practicante::where('codigo', $codigo)->first();

        // Verificar reglas de negocio
        $ultimoEvento = Bitacora::where('clave_prac', $codigo)
            ->where('fecha', $fechaHoy)
            ->latest('hora')
            ->first();

        if ($tipo === 'entrada') {
            // Verificar si ya hay algún evento registrado hoy (excepto salida)
            if ($ultimoEvento && $ultimoEvento->tipo !== 'salida') {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya registraste una entrada hoy o tienes eventos pendientes'
                ], 400);
            }
        }

        if ($tipo === 'entrada_comedor' && (!$ultimoEvento || $ultimoEvento->tipo !== 'entrada')) {
            return response()->json([
                'success' => false,
                'message' => 'No puedes registrar entrada de comedor sin haber registrado entrada primero'
            ], 400);
        }

        if ($tipo === 'salida_comedor' && (!$ultimoEvento || $ultimoEvento->tipo !== 'entrada_comedor')) {
            return response()->json([
                'success' => false,
                'message' => 'No puedes registrar salida de comedor sin haber registrado entrada de comedor primero'
            ], 400);
        }

        if ($tipo === 'salida') {
            // Primero verificar si ya registró salida
            if ($ultimoEvento && $ultimoEvento->tipo === 'salida') {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya registraste una salida hoy'
                ], 400);
            }

            // Luego verificar si tiene una entrada o salida de comedor registrada
            if (!$ultimoEvento || ($ultimoEvento->tipo !== 'entrada' && $ultimoEvento->tipo !== 'salida_comedor')) {
                return response()->json([
                    'success' => false,
                    'message' => 'No puedes registrar salida sin haber registrado entrada primero, revisa tus eventos registrados'
                ], 400);
            }
        }
        // Registrar el evento
        Bitacora::create([
            'clave_prac' => $codigo,
            'fecha' => $fechaHoy,
            'hora' => Carbon::now()->toTimeString(),
            'tipo' => $tipo,
            'descripcion' => 'Registro manual'
        ]);

        // Actualizar horas registradas si es salida
        if ($tipo === 'salida') {
            $this->actualizarHorasPracticante($codigo, $fechaHoy);
        }

        // Guardar nombre y sexo en sesión solo para entrada
        if ($tipo === 'entrada') {
            session([
                'nombre' => $practicante->nombre,
                'sexo' => $practicante->sexo
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Registro exitoso',
            'nombre' => $practicante->nombre,
            'sexo' => $practicante->sexo
        ]);
    }

    private function actualizarHorasPracticante($codigo, $fecha)
    {
        $eventos = Bitacora::where('clave_prac', $codigo)
            ->where('fecha', $fecha)
            ->orderBy('hora')
            ->get();

        $entrada = $eventos->firstWhere('tipo', 'entrada');
        $salida = $eventos->firstWhere('tipo', 'salida');

        if ($entrada && $salida) {
            $horasTrabajadas = Carbon::parse($entrada->hora)->diffInHours(Carbon::parse($salida->hora));

            Practicante::where('codigo', $codigo)
                ->increment('horas_registradas', $horasTrabajadas);
        }
    }
}