<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrera extends Model
{
    use HasFactory;

    // Definir la tabla asociada
    protected $table = 'carreras';

    // Definir la clave primaria
    protected $primaryKey = 'id_carrera';

    // Indicar que la clave primaria no es incremental
    public $incrementing = true;

    // Definir los campos que se pueden asignar masivamente
    protected $fillable = [
        'nombre_carr',
        'gerente_carr',
        'tel_gerente',
        'correo_carr',
        'id_institucion',

    ];

    // Deshabilitar las marcas de tiempo si no las necesitas
    public $timestamps = true;
    /**
     * Relación con la Institución.
     */
    public function institucion()
    {
        return $this->belongsTo(Institucion::class, 'id_institucion');
    }
}