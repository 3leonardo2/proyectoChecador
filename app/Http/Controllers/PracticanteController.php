<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Practicante; // Asegúrate de tener los modelos creados
use App\Models\Institucion;
use App\Models\Carrera;
use Illuminate\Support\Str; // Para usar funciones de string
use Illuminate\Support\Facades\DB; // Para transacciones

class PracticanteController extends Controller
{
    public function index()
    {
        $practicantes = Practicante::with('institucion')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('lista_practicantes', compact('practicantes'));
    }
    /**
     * Muestra el formulario para crear un nuevo practicante.
     */
    public function create()
    {
        // Pasamos todas las instituciones y carreras a la vista para los <datalist>
        $instituciones = Institucion::orderBy('nombre')->get();
        $carreras = Carrera::orderBy('nombre_carr')->get();

        return view('registrar_prac', compact('instituciones', 'carreras'));
    }

    /**
     * Almacena un nuevo practicante en la base de datos.
     * 
     */
    public function store(Request $request)
    {
        // 1. Validar los datos (puedes añadir más reglas según necesites)
        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'curp' => 'required|string|min:18|max:18|unique:practicantes,curp',
            'fecha_nacimiento' => 'required|date',
            'institucion_nombre' => 'required|string|max:255',
            'carrera_nombre' => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'email_personal' => 'nullable|email|unique:practicantes,email_personal',
            'telefono_personal' => 'nullable|string|max:15',
            'nombre_emergencia' => 'nullable|string|max:100',
            'telefono_emergencia' => 'nullable|string|max:15',
            'num_seguro' => 'nullable|string|max:20',
            'email_institucional' => 'nullable|email',
            'telefono_institucional' => 'nullable|string|max:20',
            'area_asignada' => 'nullable|string|max:100',
            'hora_entrada' => 'nullable|string|max:10',
            'hora_salida' => 'nullable|string|max:10',
            'horas_requeridas' => 'nullable|integer|min:0',
            'horas_registradas' => 'nullable|integer|min:0',
        ]);

        // Usar una transacción para asegurar la integridad de los datos
        DB::beginTransaction();
        try {
            // 2. Gestionar Institución (Buscar o Crear)
            // firstOrCreate buscará un registro con ese nombre, si no lo encuentra, lo creará.
            $institucion = Institucion::firstOrCreate(
                ['nombre' => $request->institucion_nombre],
                // Si la crea, puede añadir más datos si los tuvieras en otro campo
                // ['direccion' => $request->direccion_institucion] 
            );

            // 3. Gestionar Carrera (Buscar o Crear, asociada a la institución)
            $carrera = Carrera::firstOrCreate(
                [
                    'id_institucion' => $institucion->id_institucion,
                    'nombre_carr' => $request->carrera_nombre
                ]
            );

            // 4. Generar el código del practicante
            // Lógica: 3 primeras letras del nombre + 3 últimos caracteres de la CURP
            $codigo = strtoupper(
                substr($request->nombre, 0, 3) . substr($request->curp, -3)
            );


            // Asegurarse de que el código sea único (en caso de una colisión muy improbable)
            $count = Practicante::where('codigo', 'LIKE', $codigo . '%')->count();
            if ($count > 0) {
                $codigo = $codigo . ($count + 1);
            }


            // 5. Crear y guardar el nuevo practicante
            $practicante = new Practicante();
            $practicante->fill($request->all()); // Llena todos los campos definidos en $fillable del modelo

            // Asignar los valores que NO vienen directamente del formulario
            $practicante->codigo = $codigo;
            $practicante->institucion_id = $institucion->id_institucion;
            $practicante->carrera_id = $carrera->id_carrera;

            $practicante->save();

            $practicante->save();

            DB::commit(); // Si todo salió bien, confirma los cambios en la BD

            return redirect()->route('practicantes.show', $practicante->id)
                ->with('success', 'Practicante registrado exitosamente con el código: ' . $codigo);

        } catch (\Exception $e) {
            DB::rollBack(); // Si algo falla, revierte todos los cambios
            return back()->withErrors(['error' => 'Ocurrió un error al registrar al practicante: ' . $e->getMessage()])->withInput();
        }
    }
    public function getByCarrera(Request $request)
    {
        \Log::info('Solicitud getByCarrera recibida', ['carrera' => $request->input('carrera')]);

        try {
            $carreraNombre = urldecode($request->input('carrera'));

            if (empty($carreraNombre)) {
                return response()->json(['error' => 'Nombre de carrera no proporcionado'], 400);
            }

            // 1. Buscar la carrera por su nombre
            $carrera = Carrera::where('nombre_carr', 'LIKE', '%' . $carreraNombre . '%')->first();

            if (!$carrera) {
                \Log::info('No se encontró la carrera', ['nombre' => $carreraNombre]);
                return response()->json([], 200); // Devuelve un objeto vacío si no se encuentra
            }

            // 2. Devolver la información de contacto DE LA CARRERA, no de un practicante
            // Estos nombres de campo ('correo_carr', 'tel_gerente') deben coincidir con tu migración de la tabla `carreras`.
            // Basado en tu InstitucionController, los campos son 'correo_carr' y 'tel_gerente'.
            $datosCarrera = [
                'email_institucional' => $carrera->correo_carr,
                'telefono_institucional' => $carrera->tel_gerente,
            ];

            \Log::info('Resultado encontrado', ['carrera_info' => $datosCarrera]);

            return response()->json($datosCarrera);

        } catch (\Exception $e) {
            \Log::error('Error en getByCarrera: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Error interno del servidor'], 500);
        }
    }
    public function update(Request $request, $id)
    {
        // Validación básica (puedes ajustarla según tus reglas)
        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
'curp' => 'required|string|min:18|max:18|unique:practicantes,curp,' . $id . ',id_practicante',
            'fecha_nacimiento' => 'required|date',
            'institucion_nombre' => 'required|string|max:255',
            'carrera_nombre' => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
'email_personal' => 'nullable|email|unique:practicantes,email_personal,' . $id . ',id_practicante',
            'telefono_personal' => 'nullable|string|max:15',
            'nombre_emergencia' => 'nullable|string|max:100',
            'telefono_emergencia' => 'nullable|string|max:15',
            'num_seguro' => 'nullable|string|max:20',
            'email_institucional' => 'nullable|email',
            'telefono_institucional' => 'nullable|string|max:20',
            'area_asignada' => 'nullable|string|max:100',
            'hora_entrada' => 'nullable|string|max:10',
            'hora_salida' => 'nullable|string|max:10',
            'horas_requeridas' => 'nullable|integer|min:0',
            'horas_registradas' => 'nullable|integer|min:0',

        ]);

        // Buscar practicante
        $practicante = Practicante::findOrFail($id);

        // Actualizar campos
        $practicante->update($request->all());

        // Redirigir a la vista de detalles
        return redirect()->route('practicantes.show', $practicante->id_practicante)
            ->with('success', 'Practicante actualizado correctamente.');
    }

    public function show($id)
    {
        $practicante = Practicante::with(['institucion', 'carrera'])->findOrFail($id);
        return view('detallesprac', compact('practicante'));
    }
    public function edit($id)
    {
        $practicante = Practicante::with(['institucion', 'carrera'])->findOrFail($id);
        return view('edit_prac', compact('practicante'));
    }

}