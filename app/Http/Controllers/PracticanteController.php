<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log; // Para logging
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Practicante; // Asegúrate de tener los modelos creados
use App\Models\Institucion;
use App\Models\Carrera;
use Barryvdh\DomPDF\Facade\Pdf;
use Picqer\Barcode\BarcodeGeneratorPNG;
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
        \Log::info('Iniciando registro de practicante', ['request' => $request->all()]);
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
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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
            $practicante->fill($request->except('profile_image')); // Llena todos los campos definidos en $fillable del modelo

            // Asignar los valores que NO vienen directamente del formulario
            $practicante->codigo = $codigo;
            $practicante->institucion_id = $institucion->id_institucion;
            $practicante->carrera_id = $carrera->id_carrera;
            $practicante->horas_registradas = 0;

            $practicante->save();

            if ($request->hasFile('profile_image')) {
                \Log::info('Archivo recibido', [
                    'name' => $request->file('profile_image')->getClientOriginalName(),
                    'size' => $request->file('profile_image')->getSize(),
                    'mime' => $request->file('profile_image')->getMimeType()
                ]);
                // Eliminar imagen anterior si existe (para updates)
                if ($practicante->profile_image) {
                    Storage::disk('public')->delete($practicante->profile_image);
                }

                // Generar nombre único basado en ID
                $extension = $request->file('profile_image')->extension();
                $filename = 'practicante_' . $practicante->id_practicante . '.' . $extension;

                $tempPath = $request->file('profile_image')->storeAs(
                    'temp',
                    'test_upload.jpg',
                    'public'
                );
                \Log::info('Archivo guardado temporalmente en: ' . $tempPath);
                // Guardar en la carpeta personalizada
                $imagePath = $request->file('profile_image')->storeAs(
                    'fotos_practicantes',
                    $filename,
                    'public'
                );

                $practicante->profile_image = $imagePath;
                $practicante->save();
            }


            DB::commit(); // Si todo salió bien, confirma los cambios en la BD

            return redirect()->route('practicantes.show', $practicante->id_practicante)
                ->with('success', 'Practicante registrado exitosamente con el código: ' . $codigo);

        } catch (\Exception $e) {
            \Log::error('Error en store: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
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
    // En PracticanteController.php
    public function update(Request $request, $id)
    {
        // Validación completa incluyendo la imagen
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
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $practicante = Practicante::findOrFail($id);

            // 1. Gestionar Institución
            $institucion = Institucion::firstOrCreate(
                ['nombre' => $request->institucion_nombre]
            );

            // 2. Gestionar Carrera
            $carrera = Carrera::firstOrCreate(
                [
                    'id_institucion' => $institucion->id_institucion,
                    'nombre_carr' => $request->carrera_nombre
                ]
            );

            // 3. Manejo de la imagen
            if ($request->hasFile('profile_image')) {
                // Eliminar imagen anterior si existe
                if ($practicante->profile_image) {
                    Storage::disk('public')->delete($practicante->profile_image);
                }

                // Generar nombre único basado en ID
                $extension = $request->file('profile_image')->extension();
                $filename = 'practicante_' . $practicante->id_practicante . '.' . $extension;

                // Guardar en la carpeta personalizada
                $imagePath = $request->file('profile_image')->storeAs(
                    'fotos_practicantes',
                    $filename,
                    'public'
                );

                $practicante->profile_image = $imagePath;
            }

            // 4. Actualizar los campos directos del practicante
            $practicante->fill($request->except(['institucion_nombre', 'carrera_nombre', 'profile_image']));

            // 5. Asignar los IDs de las relaciones
            $practicante->institucion_id = $institucion->id_institucion;
            $practicante->carrera_id = $carrera->id_carrera;

            $practicante->save();

            DB::commit();

            return redirect()->route('practicantes.show', $practicante->id_practicante)
                ->with('success', 'Practicante actualizado correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            // Agregar logging para depuración
            \Log::error('Error al actualizar practicante: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Ocurrió un error al actualizar: ' . $e->getMessage()])->withInput();
        }
    }

    public function generarCredencial(Practicante $practicante)
    {
        $nombreCompleto = $practicante->nombre . ' ' . $practicante->apellidos;
        $area = $practicante->area_asignada;
        $clave = $practicante->codigo;

        $logoFrentePath = public_path('images/credencial/logo_presidente3.webp');
        $logoDorsoPath = public_path('images/credencial/logo_presidente2.png');

        $generator = new BarcodeGeneratorPNG();
        $barcodeImage = base64_encode($generator->getBarcode(
            $clave,
                $generator::TYPE_CODE_128,
            2,
            33
        ));

        $data = [
            'nombreCompleto' => $nombreCompleto,
            'area' => $area,
            'clave' => $clave,
            'logoFrentePath' => $logoFrentePath,
            'logoDorsoPath' => $logoDorsoPath,
            'barcodeImage' => $barcodeImage,
        ];

        $pdf = Pdf::loadView('credenciales.plantilla_gp', $data);

        // Configuración exacta del papel (12cm x 8cm cada credencial, con márgenes)
        $pdf->setPaper([0, 0, 226.77, 340.15], 'portrait');

        return $pdf->download('credencial-' . $clave . '.pdf');
    }

    public function generarReporte(Request $request, Practicante $practicante)
    {
        //Verificar si la imagen del perfil existe
        $imagenPath = $practicante->profile_image
            ? storage_path('app/public/' . $practicante->profile_image)
            : null;
        $imagenValida = ($imagenPath && file_exists($imagenPath));

        // Obtener datos básicos del practicante
        $data = [
            'practicante' => $practicante->load(['institucion', 'carrera']),
            'horas_completadas' => $practicante->horas_registradas,
            'horas_requeridas' => $practicante->horas_requeridas,
            'porcentaje_completado' => ($practicante->horas_requeridas > 0)
                ? ($practicante->horas_registradas / $practicante->horas_requeridas) * 100
                : 0,
            'imagen' => $imagenValida ? base64_encode(file_get_contents($imagenPath)) : null,
        ];

        // Verificar si se deben incluir las revisiones
        $incluirRevisiones = filter_var($request->input('incluir_revisiones', false), FILTER_VALIDATE_BOOLEAN);

        if ($incluirRevisiones) {
            $data['revisiones'] = $practicante->evaluaciones()->orderBy('fecha_evaluacion', 'desc')->get();
        }

        // Opciones para el PDF
        $pdfOptions = [
            'orientation' => 'portrait',
            'margin-top' => 10,
            'margin-right' => 10,
            'margin-bottom' => 10,
            'margin-left' => 10,
        ];

        $pdf = PDF::loadView('reportes.practicante', $data)
            ->setOptions($pdfOptions);

        return $pdf->download('reporte-practicante-' . $practicante->codigo . '.pdf');
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

    public function showEvaluaciones($id_practicante)
    {
        $practicante = Practicante::with('evaluaciones')->findOrFail($id_practicante);
        return view('lista_revisiones', compact('practicante'));
    }

}