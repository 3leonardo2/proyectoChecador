<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Practicante extends Model
{
    use HasFactory;

    /**
     * La llave primaria para el modelo.
     * Es importante definirla porque no usas el nombre 'id' por defecto.
     *
     * @var string
     */
    protected $primaryKey = 'id_practicante';

    /**
     * Los atributos que se pueden asignar masivamente.
     * Agrega aquí todos los campos de tu formulario.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        
        'nombre',
        'apellidos',
        'fecha_nacimiento',
        'sexo',
        'curp',
        'direccion',
        'email_personal',
        'telefono_personal',
        'nombre_emergencia',
        'telefono_emergencia',
        'num_seguro',
        'email_institucional',
        'telefono_institucional',
        'nivel_estudios',
        'estado_practicas',
        'area_asignada',
        'fecha_inicio',
        'fecha_final',
        'hora_entrada',
        'hora_salida',
        'horas_requeridas',
        'institucion_id',
        'carrera_id',
    ];
    public function institucion()
{
    return $this->belongsTo(Institucion::class, 'institucion_id', 'id_institucion');
}

public function carrera()
{
    return $this->belongsTo(Carrera::class, 'carrera_id', 'id_carrera');
}

}