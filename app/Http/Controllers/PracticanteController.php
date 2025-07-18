<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use App\Models\Proyecto;
use Illuminate\Http\Request;
use App\Models\Practicante; // Asegúrate de tener los modelos creados
use App\Models\Institucion;
use App\Models\Carrera;
use Barryvdh\DomPDF\Facade\Pdf;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Illuminate\Support\Str; // Para usar funciones de string
use Illuminate\Support\Facades\DB; // Para transacciones
use Illuminate\Support\Facades\Log; // Para logging

class PracticanteController extends Controller
{
    public function index()
    {
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
            ]); // Asegúrate de incluir fecha_fin

        return view('lista_practicantes', compact('practicantes'));
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
            'curp' => 'required|string|min:18|max:18|unique:practicantes,curp',
            'fecha_nacimiento' => 'required|date',
            'sexo' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'institucion_id' => 'required|exists:instituciones,id_institucion',
            'carrera_id' => 'required|exists:carreras,id_carrera',
            'fecha_inicio' => 'required|date',
            'email_personal' => 'required|email|unique:practicantes,email_personal',
            'telefono_personal' => 'nullable|string|max:15',
            'nombre_emergencia' => 'nullable|string|max:100',
            'telefono_emergencia' => 'nullable|string|max:15',
            'num_seguro' => 'nullable|string|max:20',
            'email_institucional' => 'nullable|email',
            'telefono_institucional' => 'nullable|string|max:20',
            'nivel_estudios' => 'nullable|string|max:255',
            'estado_practicas' => 'nullable|string|max:50',
            'area_asignada' => 'nullable|string|max:100',
            'fecha_final' => 'nullable|date',
            'hora_entrada' => 'nullable|string|max:10',
            'hora_salida' => 'nullable|string|max:10',
            'horas_requeridas' => 'nullable|integer|min:0',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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
            // Fusionar las reglas comunes con las reglas del proyecto
            $validated = $request->validate(array_merge($commonRules, $projectRules));

            Log::info('Validación exitosa', $validated);

            DB::beginTransaction();

            // Generar código (esta lógica está bien)
            $codigo = strtoupper(
                substr($validated['nombre'], 0, 3) . substr($validated['curp'], -3)
            );

            $count = Practicante::where('codigo', 'LIKE', $codigo . '%')->count();
            if ($count > 0) {
                $codigo = $codigo . ($count + 1);
            }

            // Crear practicante
            $practicante = new Practicante();
            // Llenar el practicante con los datos validados
            // Asegúrate de que todos los campos del formulario que se mapean directamente
            // a la tabla practicantes estén en $fillable en el modelo Practicante.
            $practicante->fill($validated); // Usa $validated en lugar de $request->except()
            $practicante->codigo = $codigo;
            $practicante->horas_registradas = 0; // Se inicializa a 0 al crear

            // Manejo de imagen (esta lógica está bien, pero usa $validated para la ruta)
            if (isset($validated['profile_image'])) { // Verifica si el campo de imagen está presente en los datos validados
                $imagePath = $validated['profile_image']->store( // Accede al archivo a través de $validated
                    'fotos_practicantes',
                    'public'
                );
                $practicante->profile_image = $imagePath;
            }

            // Manejo del proyecto
            // El `proyecto_id` se inicializa a null por defecto en la base de datos si es nullable,
            // o se asigna más abajo si se crea un proyecto.
            $practicante->proyecto_id = null; // Reiniciar o asegurar que es null por defecto

            if ($request->has('incluir_proyecto') && $request->incluir_proyecto == 'on') {
                $proyecto = Proyecto::create([
                    'nombre_proyecto' => $validated['nombre_proyecto'],
                    'descripcion_proyecto' => $validated['descripcion_proyecto'] ?? null,
                    'area_proyecto' => $validated['area_proyecto'] ?? null,
                ]);
                // Asignar directamente a la propiedad del modelo Practicante
                $practicante->proyecto_id = $proyecto->id_proyecto;
            }

            $practicante->save(); // Guarda el practicante con todos sus datos, incluyendo proyecto_id

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
            return back()->with('error', 'Error al registrar: ' . $e->getMessage())->withInput();
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
        $practicante = Practicante::findOrFail($id);

        $projectRules = [];
        // Se define 'incluir_proyecto' como parte de las reglas para que se valide
        // correctamente al momento de fusionar
        $commonRules = [
            'nombre' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'curp' => 'required|string|min:18|max:18|unique:practicantes,curp,' . $practicante->id_practicante . ',id_practicante',
            'fecha_nacimiento' => 'required|date',
            'institucion_id' => 'required|exists:instituciones,id_institucion',
            'carrera_id' => 'required|exists:carreras,id_carrera',
            'fecha_inicio' => 'required|date',
            'email_personal' => 'nullable|email|unique:practicantes,email_personal,' . $practicante->id_practicante . ',id_practicante',
            'telefono_personal' => 'nullable|string|max:15',
            'nombre_emergencia' => 'nullable|string|max:100',
            'nivel_estudios' => 'nullable|string|max:255',
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
            'incluir_proyecto' => 'nullable|string', // Importante para que el request->has() funcione correctamente en la validación
        ];

        if ($request->has('incluir_proyecto') && $request->incluir_proyecto == 'on') {
            $projectRules = [
                'nombre_proyecto' => 'required|string|max:255',
                'descripcion_proyecto' => 'nullable|string',
                'area_proyecto' => 'nullable|string|max:255',
            ];
        }

        // ASIGNA el resultado de validate() a $validatedData
        $validatedData = $request->validate(array_merge($commonRules, $projectRules));

        DB::beginTransaction();
        try {
            // No necesitas volver a buscar el practicante aquí, ya lo tienes al principio
            // $practicante = Practicante::findOrFail($id); // <-- Elimina esta línea duplicada

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
            } else if ($request->input('profile_image_cleared')) { // Si el usuario marcó para limpiar la imagen
                if ($practicante->profile_image && Storage::disk('public')->exists($practicante->profile_image)) {
                    Storage::disk('public')->delete($practicante->profile_image);
                }
                $practicante->profile_image = null;
            }


            // 4. Manejo del proyecto
            if (!$request->has('incluir_proyecto') && $practicante->proyecto_id) {
                // Si el checkbox se desmarcó y había un proyecto asociado, lo eliminamos y desvinculamos
                $practicante->proyecto->delete(); // Esto elimina el registro del proyecto de la tabla `proyectos`
                $practicante->proyecto_id = null; // Esto establece la clave foránea a NULL en `practicantes`
            }
            // Si el checkbox está marcado, creamos o actualizamos el proyecto
            elseif ($request->has('incluir_proyecto') && $request->incluir_proyecto == 'on') {
                if ($practicante->proyecto_id) {
                    // Actualizar proyecto existente
                    $practicante->proyecto->update([
                        'nombre_proyecto' => $validatedData['nombre_proyecto'],
                        'descripcion_proyecto' => $validatedData['descripcion_proyecto'] ?? null,
                        'area_proyecto' => $validatedData['area_proyecto'] ?? null,
                    ]);
                } else {
                    // Crear nuevo proyecto y asignarlo
                    $proyecto = Proyecto::create([
                        'nombre_proyecto' => $validatedData['nombre_proyecto'],
                        'descripcion_proyecto' => $validatedData['descripcion_proyecto'] ?? null,
                        'area_proyecto' => $validatedData['area_proyecto'] ?? null,
                    ]);
                    $practicante->proyecto_id = $proyecto->id_proyecto;
                }
            } else {
                // Si el checkbox no está marcado y no había un proyecto, o si el checkbox se desmarca
                // y ya se manejó la eliminación arriba, no hacemos nada con el proyecto_id aquí.
                // Si no había proyecto_id y el checkbox no está, simplemente se mantiene null.
                // Si se desmarcó y se eliminó el proyecto, ya se estableció a null arriba.
            }

            // 5. Actualizar los campos directos del practicante
            // Usa $validatedData para llenar el modelo, excluyendo 'incluir_proyecto'
            // Los campos 'institucion_nombre', 'carrera_nombre' no deberían estar en $validatedData
            // si no son campos reales del formulario o vienen de selects con IDs.
            // Si el nombre de la institución y carrera no se guardan directamente en el modelo
            // del practicante, puedes usar $request->except(['incluir_proyecto'])
            // o $validatedData si quieres ser más explícito.
            // La validación ya se encarga de que vengan los IDs correctos.
            $practicante->fill($validatedData); // Esto llenará todos los campos validados

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
        $pdf->setPaper([0, 0, 226.77, 340.15], 'portrait');

        // Cambia download() por stream() para abrir en el navegador
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
        $practicante = Practicante::with(['institucion', 'carrera'])->findOrFail($id);
        $instituciones = Institucion::orderBy('nombre')->get();
        $proyectos = Proyecto::orderBy('nombre_proyecto')->get();

        return view('edit_prac', compact('practicante', 'instituciones', 'proyectos'));
    }

    public function showEvaluaciones($id_practicante)
    {
        $practicante = Practicante::with('evaluaciones')->findOrFail($id_practicante);
        return view('lista_revisiones', compact('practicante'));
    }

}