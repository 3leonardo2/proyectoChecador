<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Models\Proyecto;
use Illuminate\Http\Request;
use App\Models\Practicante;
use App\Models\Institucion;
use App\Models\Carrera;
use Barryvdh\DomPDF\Facade\Pdf;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Illuminate\Support\Str; 
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Log; // Para logging

class PracticanteController extends Controller
{
    public function index()
    {
        $mostrarActivos = true;
        $practicantes = Practicante::with('institucion')
            ->orderBy('created_at', 'desc')
            ->get([
                'id_practicante',
                'codigo',
                'nombre',
                'apellidos',
                'area_asignada',
                'institucion_id',
                'estado_practicas',
                'fecha_final'
            ]); 
        return view('lista_practicantes', compact('practicantes','mostrarActivos'));
    }
    /**
     * Muestra el formulario para crear un nuevo practicante.
     */
    public function create()
    {
        // Pasamos todas las instituciones y carreras a la vista para los <datalist>
        $proyectos = Proyecto::orderBy('nombre_proyecto')->get();
        $instituciones = Institucion::orderBy('nombre')->get();
        $carreras = Carrera::orderBy('nombre_carr')->get();

        return view('registrar_prac', compact('instituciones', 'carreras', 'proyectos'));
    }

    public function store(Request $request)
    {
        Log::info('Iniciando registro de practicante', ['request' => $request->all()]);

        $projectRules = [];
        $commonRules = [
            'nombre' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'curp' => 'string|min:18|max:18|unique:practicantes,curp',
            'fecha_nacimiento' => 'required|date',
            'sexo' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'institucion_id' => 'required|exists:instituciones,id_institucion',
            'carrera_id' => 'required|exists:carreras,id_carrera',
            'fecha_inicio' => 'required|date',
            'email_personal' => 'nullable|email|unique:practicantes,email_personal',
            'telefono_personal' => 'nullable|string|max:15',
            'nombre_emergencia' => 'nullable|string|max:100',
            'telefono_emergencia' => 'nullable|string|max:15',
            'num_seguro' => 'nullable|string|max:11',
            'email_institucional' => 'nullable|email',
            'telefono_institucional' => 'nullable|string|max:20',
            'nivel_estudios' => 'nullable|string|max:255',
            'estado_practicas' => 'nullable|string|max:50',
            'area_asignada' => 'nullable|string|max:100',
            'fecha_final' => 'nullable|date',
            'hora_entrada' => 'nullable|string|max:10',
            'hora_salida' => 'nullable|string|max:10',
            'acceso_comedor' => 'nullable|in:0,1',
            'horas_requeridas' => 'nullable|integer|min:0',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'incluir_proyecto' => 'nullable|string',
        ];

        if ($request->has('incluir_proyecto') && $request->incluir_proyecto == 'on') {
            $projectRules = [
                'nombre_proyecto' => 'required|string|max:255',
                'descripcion_proyecto' => 'nullable|string',
                'area_proyecto' => 'nullable|string|max:255',
            ];
        }

        try {
            Log::info('Validando datos...');
            $validated = $request->validate(array_merge($commonRules, $projectRules));
            Log::info('Datos validados correctamente:', $validated);

            // Convertir campos vacíos a null
            foreach (['email_personal', 'email_institucional', 'telefono_personal', 'telefono_institucional', 'nombre_emergencia', 'telefono_emergencia', 'direccion'] as $campo) {
                if (isset($validated[$campo]) && $validated[$campo] === '') {
                    $validated[$campo] = null;
                }
            }

            Log::info('Validación exitosa', $validated);

            DB::beginTransaction();

            // Generar código
            $lastPracticante = Practicante::orderBy('id_practicante', 'desc')->first();
            $lastCode = $lastPracticante ? $lastPracticante->codigo : 'A000';

            $prefix = substr($lastCode, 0, 1);
            $number = (int) substr($lastCode, 1);

            if ($number >= 999) {
                $prefix = chr(ord($prefix) + 1);
                $number = 1;
            } else {
                $number++;
            }
            $codigo = $prefix . str_pad($number, 3, '0', STR_PAD_LEFT);

            //Crear Practicante
            $practicante = new Practicante();
            $practicante->fill($validated);
            $practicante->codigo = $codigo;
            $practicante->horas_registradas = 0;
            $practicante->acceso_comedor = $request->has('acceso_comedor') ? true : false;

            if (isset($validated['profile_image'])) {
                $imagePath = $validated['profile_image']->store('fotos_practicantes', 'public');
                $practicante->profile_image = $imagePath;
            }

            $practicante->proyecto_id = null;

            if ($request->has('incluir_proyecto') && $request->incluir_proyecto == 'on') {
                $proyecto = Proyecto::create([
                    'nombre_proyecto' => $validated['nombre_proyecto'],
                    'descripcion_proyecto' => $validated['descripcion_proyecto'] ?? null,
                    'area_proyecto' => $validated['area_proyecto'] ?? null,
                ]);
                $practicante->proyecto_id = $proyecto->id_proyecto;
            }

            $practicante->save();

            DB::commit();

            Log::info('Practicante registrado exitosamente', ['codigo' => $codigo]);

            return redirect()->route('practicantes.show', $practicante->id_practicante)
                ->with('success', 'Practicante registrado exitosamente con el código: ' . $codigo);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Error de validación', ['errors' => $e->errors()]);
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error en store: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            DB::rollBack();

            return back()->with('error')->withInput();
        }
    }



    public function getByCarrera(Request $request)
    {
        try {
            $carreraId = $request->input('carrera_id');

            if (empty($carreraId)) {
                return response()->json(['error' => 'ID de carrera no proporcionado'], 400);
            }

            $carrera = Carrera::find($carreraId);

            if (!$carrera) {
                return response()->json([], 200);
            }

            return response()->json([
                'email_institucional' => $carrera->correo_carr,
                'telefono_institucional' => $carrera->tel_gerente,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error interno del servidor'], 500);
        }
    }


    public function update(Request $request, $id)
    {
        Log::info('Datos recibidos para actualización:', $request->all());
        $practicante = Practicante::findOrFail($id);

        $projectRules = [];
        $commonRules = [
            'nombre' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'curp' => 'string|min:18|max:18' . $practicante->id_practicante . ',id_practicante',
            'fecha_nacimiento' => 'required|date',
            'institucion_id' => 'required|exists:instituciones,id_institucion',
            'carrera_id' => 'required|exists:carreras,id_carrera',
            'sexo' => 'nullable|string|max:20',
            'fecha_inicio' => 'required|date',
            'email_personal' => 'nullable|email|unique:practicantes,email_personal,' . $practicante->id_practicante . ',id_practicante',
            'telefono_personal' => 'nullable|string|max:15',
            'nombre_emergencia' => 'nullable|string|max:100',
            'nivel_estudios' => 'nullable|string|max:255',
            'estado_practicas' => 'nullable|string|max:50',
            'direccion' => 'nullable|string|max:255',
            'telefono_emergencia' => 'nullable|string|max:15',
            'num_seguro' => 'nullable|string|max:20',
            'email_institucional' => 'nullable|email',
            'telefono_institucional' => 'nullable|string|max:20',
            'area_asignada' => 'nullable|string|max:100',
            'hora_entrada' => 'nullable|string|max:10',
            'hora_salida' => 'nullable|string|max:10',
            'horas_requeridas' => 'nullable|integer|min:0',
            'horas_registradas' => 'nullable|integer|min:0',
            'acceso_comedor' => 'nullable|in:0,1',

            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'incluir_proyecto' => 'nullable|string',
        ];

        if ($request->has('incluir_proyecto') && $request->incluir_proyecto == 'on') {
            $projectRules = [
                'nombre_proyecto' => 'required|string|max:255',
                'descripcion_proyecto' => 'nullable|string',
                'area_proyecto' => 'nullable|string|max:255',
            ];
        }

        try {
            $validatedData = $request->validate(array_merge($commonRules, $projectRules));
            Log::info('Datos validados:', $validatedData);
            DB::beginTransaction();

            // 3. Manejo de la imagen
            if ($request->hasFile('profile_image')) {
                // Eliminar imagen anterior si existe
                if ($practicante->profile_image && Storage::disk('public')->exists($practicante->profile_image)) {
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

                // Eliminar el campo de imagen de los datos validados para que fill() no lo sobrescriba
                unset($validatedData['profile_image']);
            } else if ($request->input('profile_image_cleared')) {
                if ($practicante->profile_image && Storage::disk('public')->exists($practicante->profile_image)) {
                    Storage::disk('public')->delete($practicante->profile_image);
                }
                $practicante->profile_image = null;
            }

            // 4. Manejo del proyecto
            if (!$request->has('incluir_proyecto') && $practicante->proyecto_id) {
                $practicante->proyecto->delete();
                $practicante->proyecto_id = null;
            } elseif ($request->has('incluir_proyecto') && $request->incluir_proyecto == 'on') {
                if ($practicante->proyecto_id) {
                    $practicante->proyecto->update([
                        'nombre_proyecto' => $validatedData['nombre_proyecto'],
                        'descripcion_proyecto' => $validatedData['descripcion_proyecto'] ?? null,
                        'area_proyecto' => $validatedData['area_proyecto'] ?? null,
                    ]);
                } else {
                    $proyecto = Proyecto::create([
                        'nombre_proyecto' => $validatedData['nombre_proyecto'],
                        'descripcion_proyecto' => $validatedData['descripcion_proyecto'] ?? null,
                        'area_proyecto' => $validatedData['area_proyecto'] ?? null,
                    ]);
                    $practicante->proyecto_id = $proyecto->id_proyecto;
                }
            }

            $practicante->acceso_comedor = $request->has('acceso_comedor') ? true : false;

            // Llenar el modelo con los datos validados (excluyendo la imagen)
            $practicante->fill($validatedData);

            $practicante->save();

            DB::commit();

            return redirect()->route('practicantes.show', $practicante->id_practicante)
                ->with('success', 'Practicante actualizado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar practicante: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Ocurrió un error al actualizar: ' . $e->getMessage()])->withInput();
        }
    }

    public function generarCredencial(Practicante $practicante)
    {
        $imagenPath = $practicante->profile_image
            ? storage_path('app/public/' . $practicante->profile_image)
            : null;
        $imagenValida = ($imagenPath && file_exists($imagenPath));
        $nombreCompleto = $practicante->nombre . ' ' . $practicante->apellidos;
        $area = $practicante->area_asignada;
        $clave = $practicante->codigo;

        $logoFrentePath = public_path('images/credencial/logo_presidente3.png');
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
            'imagen' => $imagenValida ? base64_encode(file_get_contents($imagenPath)) : null,
        ];
        $pdf = Pdf::loadView('credenciales.plantilla_gp', $data);
        $pdf->setPaper([0, 0, 552, 255], 'landscape');
        return $pdf->stream('credencial-' . $clave . '.pdf');
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
        $practicante = Practicante::with(['institucion', 'carrera', 'proyecto'])->findOrFail($id);
        return view('detallesprac', compact('practicante'));
    }
    public function edit($id)
    {
        $practicante = Practicante::with(['institucion', 'carrera.institucion', 'proyecto'])->findOrFail($id);
        $instituciones = Institucion::orderBy('nombre')->get();
        $proyectos = Proyecto::orderBy('nombre_proyecto')->get();

        return view('edit_prac', compact('practicante', 'instituciones', 'proyectos'));
    }

    public function showEvaluaciones($id_practicante)
    {
        $practicante = Practicante::with('evaluaciones')->findOrFail($id_practicante);
        return view('lista_revisiones', compact('practicante'));
    }

    public function destroy($id)
    {
        try {
            // Iniciar una transacción de base de datos para asegurar que todas las operaciones se completen o ninguna
            DB::beginTransaction();

            $practicante = Practicante::findOrFail($id);

            // 1. Eliminar los registros dependientes de la tabla "bitacora"
            $practicante->bitacora()->delete();

            // 2. Eliminar los registros dependientes de la tabla "evaluaciones"
            $practicante->evaluaciones()->delete();

            // 3. Eliminar los registros dependientes de la tabla "practicantes_proyectos" (si existe)
            // Nota: Asegúrate de que tu modelo Practicante tenga una relación definida para esta tabla
            $practicante->proyectos()->detach(); // Usar detach para tablas pivote (muchos a muchos)

            // 4. Eliminar el proyecto asociado si existe
            // Aquí asumimos que un proyecto puede ser eliminado si su practicante principal se va.
            if ($practicante->proyecto) {
                $practicante->proyecto->delete();
            }

            // Opcional: Eliminar la imagen de perfil asociada si existe
            if ($practicante->profile_image) {
                Storage::disk('public')->delete($practicante->profile_image);
            }

            // 5. Eliminar el registro del practicante
            $practicante->delete();

            // Si todo va bien, confirmar la transacción
            DB::commit();

            return redirect()->route('practicantes.index')->with('success', 'Practicante y sus registros asociados eliminados exitosamente.');
        } catch (\Exception $e) {
            // En caso de error, revertir todas las operaciones
            DB::rollBack();

            Log::error('Error al eliminar practicante: ' . $e->getMessage(), ['id' => $id, 'trace' => $e->getTraceAsString()]);

            return back()->with('error', 'Error inesperado al eliminar el practicante: ' . $e->getMessage());
        }
    }
}
