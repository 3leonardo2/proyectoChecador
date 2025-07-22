<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use App\Models\Practicante;
use Illuminate\Http\Request;
use App\Models\Aviso;
use App\Models\ImagenAviso;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BitacoraController extends Controller
{
    public function index()
    {
        // Primero desactivar imágenes que hayan expirado
        ImagenAviso::where('fecha_fin', '<', now())
            ->where('activo', true)
            ->update(['activo' => false]);

        $imagenesAvisos = ImagenAviso::where('fecha_inicio', '<=', now())
            ->where('fecha_fin', '>=', now())
            ->where('activo', true)
            ->orderBy('fecha_inicio', 'desc')
            ->get();

        $avisos = Aviso::where('fecha_inicio', '<=', now())
            ->where('fecha_fin', '>=', now())
            ->orderBy('fecha_inicio', 'desc')
            ->get();

        return view('bitacora', compact('imagenesAvisos', 'avisos'));
    }
    public function registrarEvento(Request $request)
    {
        $codigo = $request->codigo;
        if ($codigo === null || $codigo === '') {
            return response()->json([
                'success' => false,
                'message' => 'Ingrese un código de practicante válido.'
            ], 400);
        }
        $request->validate([
            'codigo' => 'required|string|exists:practicantes,codigo',
            'tipo' => 'required|in:entrada,entrada_comedor,salida_comedor,salida'
        ]);
        
        $tipo = $request->tipo;
        $fechaHoy = Carbon::today()->toDateString();
        $practicante = Practicante::where('codigo', $codigo)->first();

        $successMessages = [
            'entrada' => '¡Entrada registrada! Bienvenid@ ' . $practicante->nombre,
            'entrada_comedor' => 'Hora de comida registrada. ¡Buen provecho!',
            'salida_comedor' => 'Salida del comedor registrada. Continúa con tus actividades.',
            'salida' => 'Salida registrada. ¡Hasta mañana, ' . $practicante->nombre . '!'
        ];

        $ultimoEvento = Bitacora::where('clave_prac', $codigo)
            ->where('fecha', $fechaHoy)
            ->latest('hora')
            ->first();

        if ($tipo === 'entrada' && $ultimoEvento && $ultimoEvento->tipo !== 'salida') {
            return response()->json([
                'success' => false,
                'message' => 'Ya registraste una entrada hoy. No puedes registrar otra sin una salida previa.'
            ], 400);
        }

        if ($tipo === 'entrada_comedor' && (!$ultimoEvento || $ultimoEvento->tipo !== 'entrada')) {
            return response()->json([
                'success' => false,
                'message' => 'Debes registrar tu entrada principal antes de usar el comedor.'
            ], 400);
        }

        if ($tipo === 'salida_comedor' && (!$ultimoEvento || $ultimoEvento->tipo !== 'entrada_comedor')) {
            return response()->json([
                'success' => false,
                'message' => 'No hay un registro de entrada al comedor para esta sesión.'
            ], 400);
        }

        if ($tipo === 'salida') {
            if ($ultimoEvento && $ultimoEvento->tipo === 'salida') {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya registraste una salida hoy. No puedes registrar otra.'
                ], 400);
            }
            if (!$ultimoEvento || ($ultimoEvento->tipo !== 'entrada' && $ultimoEvento->tipo !== 'salida_comedor')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Registra una entrada o salida del comedor antes de tu salida final.'
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
            $genero = match (strtolower($practicante->sexo)) {
                'hombre' => 'O',
                'mujer' => 'A',
                default => '@' // Para "otro" usaremos un carácter neutro
            };

            session([
                'welcome_message' => [
                    'nombre' => $practicante->nombre,
                    'genero' => $genero
                ]
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => $successMessages[$tipo],
            'nombre' => $practicante->nombre,
            'sexo' => $practicante->sexo
        ]);
    }

    public function registrarEventoAutomatico(Request $request)
{
    $codigo = $request->codigo;
    if (!$codigo) {
        return response()->json([
            'success' => false,
            'message' => 'Ingrese un código de practicante válido.'
        ], 400);
    }

    $practicante = Practicante::where('codigo', $codigo)->first();
    if (!$practicante) {
        return response()->json([
            'success' => false,
            'message' => 'Código de practicante no encontrado.'
        ], 404);
    }

    $fechaHoy = now()->toDateString();
    $ultimoEvento = Bitacora::where('clave_prac', $codigo)
        ->where('fecha', $fechaHoy)
        ->latest('hora')
        ->first();

    // Determinar el siguiente tipo de evento
    $siguiente = match(optional($ultimoEvento)->tipo) {
        'salida', null => 'entrada',
        'entrada' => 'entrada_comedor',
        'entrada_comedor' => 'salida_comedor',
        'salida_comedor' => 'salida',
        default => 'entrada'
    };

    // Registrar el evento
    Bitacora::create([
        'clave_prac' => $codigo,
        'fecha' => $fechaHoy,
        'hora' => now()->toTimeString(),
        'tipo' => $siguiente,
        'descripcion' => 'Registro automático'
    ]);

    // Si es salida, actualiza horas
    if ($siguiente === 'salida') {
        $this->actualizarHorasPracticante($codigo, $fechaHoy);
    }

    $mensajes = [
        'entrada' => '¡Entrada registrada! Bienvenid@ ' . $practicante->nombre,
        'entrada_comedor' => 'Hora de comida registrada. ¡Buen provecho!',
        'salida_comedor' => 'Salida del comedor registrada. Continúa con tus actividades.',
        'salida' => 'Salida registrada. ¡Hasta mañana, ' . $practicante->nombre . '!'
    ];

    // Verifica si solo hay dos eventos hoy para este practicante
    $eventosHoy = Bitacora::where('clave_prac', $codigo)
        ->where('fecha', $fechaHoy)
        ->orderBy('hora')
        ->get();

    if ($eventosHoy->count() == 2) {
        $primerEvento = $eventosHoy[0];
        $segundoEvento = $eventosHoy[1];

        // Si el segundo evento es 'entrada_comedor', actualizarlo a 'salida'
        if ($segundoEvento->tipo === 'entrada_comedor') {
            $segundoEvento->tipo = 'salida';
            $segundoEvento->descripcion = 'Salida automática (no usó comedor)';
            $segundoEvento->save();

            // Actualiza horas
            $this->actualizarHorasPracticante($codigo, $fechaHoy);

            return response()->json([
                'success' => true,
                'message' => '¡Salida registrada! Que tengas buen día.',
                'tipo_registrado' => 'salida'
            ]);
        }
    }

    return response()->json([
        'success' => true,
        'message' => $mensajes[$siguiente],
        'tipo_registrado' => $siguiente
    ]);
}

    private function actualizarHorasPracticante($codigo, $fecha)
    {
        // 1. Obtener eventos del día ordenados cronológicamente
        $eventos = Bitacora::where('clave_prac', $codigo)
            ->where('fecha', $fecha)
            ->orderBy('hora')
            ->get();

        // 2. Filtrar solo eventos relevantes (entrada/salida)
        $entrada = $eventos->firstWhere('tipo', 'entrada');
        $salida = $eventos->firstWhere('tipo', 'salida');

        if ($entrada && $salida) {
            // 3. Calcular horas trabajadas con precisión (en minutos)
            $horaEntrada = Carbon::parse($entrada->hora);
            $horaSalida = Carbon::parse($salida->hora);

            // 4. Restar tiempo de comida si aplica (opcional)
            $entradaComedor = $eventos->firstWhere('tipo', 'entrada_comedor');
            $salidaComedor = $eventos->firstWhere('tipo', 'salida_comedor');

            $minutosTrabajados = $horaEntrada->diffInMinutes($horaSalida);

            if ($entradaComedor && $salidaComedor) {
                $minutosComida = Carbon::parse($entradaComedor->hora)
                    ->diffInMinutes(Carbon::parse($salidaComedor->hora));
                $minutosTrabajados -= $minutosComida;
            }

            // 5. Convertir a horas decimales (ej: 4.5 horas = 4 horas y 30 mins)
            $horasTrabajadas = round($minutosTrabajados / 60, 2);

            // 6. Actualizar el acumulado (usando transacción para consistencia)
            DB::transaction(function () use ($codigo, $horasTrabajadas) {
                Practicante::where('codigo', $codigo)
                    ->increment('horas_registradas', $horasTrabajadas);
            });
        }
    }
}