<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Institucion;
use App\Models\Carrera;
use App\Models\Practicante;
use Illuminate\Support\Facades\DB; // Para usar transacciones
use Illuminate\Support\Facades\Log; // Para registrar errores

class InstitucionController extends Controller
{
    /**
     * Muestra el formulario para crear una nueva institución.
     */
    public function create()
    {
        return view('registrar_insti');
    }

    /**
     * Almacena una nueva institución y sus carreras en la base de datos.
     */
    public function store(Request $request)
    {
        Log::info('Datos recibidos:', $request->all());

        $validatedData = $request->validate([
            'nombre_ins' => 'required|string|max:255|unique:instituciones,nombre',
            'acronimo_ins' => 'required|string|max:255|unique:instituciones,acronimo',
            'direccion_ins' => 'required|string|max:255',
            'telefono_ins' => 'required|string|max:20',
            'correo_ins' => 'email|max:255|unique:instituciones,correo',
            'carreras' => 'nullable|array',
            'carreras.*.nombre_carr' => 'required_with:carreras|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $institucion = Institucion::create([
                'nombre' => $request->nombre_ins,
                'acronimo' => $request->acronimo_ins,
                'direccion' => $request->direccion_ins,
                'telefono' => $request->telefono_ins,
                'correo' => $request->correo_ins,
            ]);

            Log::info('Datos validados:', $validatedData);

            if ($request->has('carreras')) {
                foreach ($request->carreras as $carreraData) {
                    if (!empty($carreraData['nombre_carr'])) {
                        Carrera::create([
                            'id_institucion' => $institucion->id_institucion,
                            'nombre_carr' => $carreraData['nombre_carr'],
                            'gerente_carr' => $carreraData['gerente_carr'],
                            'tel_gerente' => $carreraData['telefono_carr'] ?? null,
                            'correo_carr' => $carreraData['correo_carr'],
                        ]);
                    }
                }
            }

            DB::commit();

            // Siempre devolver JSON para las solicitudes AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => '¡Institución y carreras registradas exitosamente!',
                    'institucion' => $institucion
                ]);
            }

            return redirect()->route('instituciones.create')
                ->with('success', '¡Institución registrada exitosamente!');

        } catch (\Exception $e) {

            DB::rollBack();
            Log::error('Error al registrar institución: ' . $e->getMessage());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ocurrió un error al guardar la institución: ' . $e->getMessage()
                ], 500);
            }
            Log::error('Error general:', ['message' => $e->getMessage(), 'trace' => $e->getTrace()]);
            return back()->withErrors(['error' => 'Ocurrió un error al guardar la institución.'])->withInput();
        }
    }

    public function index()
    {
        $instituciones = Institucion::withCount('carreras')->orderBy('nombre')->get();
        return view('lista_instituciones', compact('instituciones'));
    }

    public function getCarreras($id_institucion)
    {
        try {
            $carreras = Carrera::where('id_institucion', $id_institucion)
                ->select('id_carrera', 'nombre_carr', 'gerente_carr', 'tel_gerente', 'correo_carr')
                ->get();

            return response()->json([
                'success' => true,
                'carreras' => $carreras
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener carreras: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit($id_institucion)
    {
        $institucion = Institucion::with('carreras')->findOrFail($id_institucion);
        return view('editar_institucion', compact('institucion'));
    }
    public function update(Request $request, $id_institucion)
    {
        DB::beginTransaction();
        try {
            $institucion = Institucion::findOrFail($id_institucion);
            $institucion->update($request->only(['nombre', 'direccion', 'telefono', 'correo']));

            if ($request->has('carreras')) {
                foreach ($request->carreras as $carreraData) {
                    if (isset($carreraData['_destroy'])) {
                        Carrera::destroy($carreraData['id_carrera']);
                        continue;
                    }

                    if (isset($carreraData['id_carrera'])) {
                        $carrera = Carrera::find($carreraData['id_carrera']);
                        if ($carrera) {
                            $carrera->update([
                                'nombre_carr' => $carreraData['nombre_carr'],
                                'gerente_carr' => $carreraData['gerente_carr'],
                                'tel_gerente' => $carreraData['telefono_carr'],
                                'correo_carr' => $carreraData['correo_carr']
                            ]);
                        }
                    } else {
                        if (!empty($carreraData['nombre_carr'])) {
                            Carrera::create([
                                'id_institucion' => $id_institucion,
                                'nombre_carr' => $carreraData['nombre_carr'],
                                'gerente_carr' => $carreraData['gerente_carr'],
                                'tel_gerente' => $carreraData['telefono_carr'],
                                'correo_carr' => $carreraData['correo_carr']
                            ]);
                        }
                    }
                }
            }

            DB::commit();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Institución actualizada correctamente'
                ]);
            }

            return redirect()->route('instituciones.index')
                ->with('success', 'Institución actualizada correctamente');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar: ' . $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Error al actualizar: ' . $e->getMessage()]);
        }
    }

    public function destroy(Institucion $institucion)
    {
        try {
            // No necesitamos una verificación aquí si la haremos en JS con confirmación
            // Pero si la quisieras añadir como una última capa de seguridad:
            $practicantesCount = $this->getAssociatedPractitionersCount($institucion->id_institucion);

            if ($practicantesCount > 0) {
                // Opcional: puedes retornar un mensaje de error si no quieres permitir la eliminación
                // sin confirmación explícita o si el front-end falló en la verificación.
                // return redirect()->back()->with('error', 'No se puede eliminar la institución porque tiene practicantes asociados. 
                // Por favor, elimínelos primero o use la confirmación adecuada.');
            }

            $institucion->delete();
            return redirect()->route('instituciones.index')->with('success', 'Institución eliminada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al eliminar la institución: ' . $e->getMessage());
        }
    }
    public function checkPracticantes($id_institucion)
    {
        $institucion = Institucion::find($id_institucion);

        if (!$institucion) {
            return response()->json(['exists' => false, 'count' => 0, 'message' => 'Institución no encontrada'], 404);
        }

        // Obtener todas las carreras asociadas a esta institución
        $carrerasIds = $institucion->carreras->pluck('id_carrera');

        // Contar el número de practicantes asociados a esas carreras
        $practicantesCount = Practicante::whereIn('carrera_id', $carrerasIds)->count();

        return response()->json([
            'exists' => $practicantesCount > 0,
            'count' => $practicantesCount,
            'message' => $practicantesCount > 0 ?
                "Esta institución tiene {$practicantesCount} practicante(s) asociado(s) a través de sus carreras. Eliminarlos resultará en la eliminación de estos practicantes." :
                "Esta institución no tiene practicantes asociados."
        ]);
    }

    private function getAssociatedPractitionersCount($id_institucion)
    {
        $institucion = Institucion::find($id_institucion);
        if (!$institucion) {
            return 0;
        }
        $carrerasIds = $institucion->carreras->pluck('id_carrera');
        return Practicante::whereIn('carrera_id', $carrerasIds)->count();
    }

}