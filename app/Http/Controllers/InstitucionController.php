<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Institucion;
use App\Models\Carrera;
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
        // 1. Validar los datos del formulario
        $validatedData = $request->validate([
            'nombre_ins' => 'required|string|max:255|unique:instituciones,nombre',
            'direccion_ins' => 'required|string|max:255',
            'telefono_ins' => 'required|string|max:20',
            'correo_ins' => 'email|max:255|unique:instituciones,correo',
            'carreras' => 'nullable|array',
            'carreras.*.nombre_carr' => 'required_with:carreras|string|max:255',
        ]);

        // 2. Usar una transacción para asegurar que todo se guarde correctamente
        DB::beginTransaction();
        try {
            // 3. Crear la Institución
            $institucion = Institucion::create([
                'nombre' => $request->nombre_ins,
                'direccion' => $request->direccion_ins,
                'telefono' => $request->telefono_ins,
                'correo' => $request->correo_ins,
            ]);

            // 4. Crear las Carreras y asociarlas a la Institución
            if ($request->has('carreras')) {
                foreach ($request->carreras as $carreraData) {
                    // Solo procesar si el nombre de la carrera no está vacío
                    if (!empty($carreraData['nombre_carr'])) {
                        Carrera::create([
                            'id_institucion' => $institucion->id_institucion,
                            'nombre_carr' => $carreraData['nombre_carr'],
                            'gerente_carr' => $carreraData['gerente_carr'],
                            'tel_gerente' => $carreraData['telefono_carr'] ?? null, // Usar el nombre correcto del input
                            'correo_carr' => $carreraData['correo_carr'],
                        ]);
                    }
                }
            }

            DB::commit(); // Confirmar los cambios si todo salió bien

            return redirect()->route('instituciones.create')->with('success', '¡Institución registrada exitosamente!');

        } catch (\Exception $e) {
            DB::rollBack(); // Revertir los cambios si algo falla
            Log::error('Error al registrar institución: ' . $e->getMessage()); // Registrar el error
            
            return back()->withErrors(['error' => 'Ocurrió un error al guardar la institución.'])->withInput();
        }
    }
}