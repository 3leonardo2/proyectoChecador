<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Institucion extends Model
{
    use HasFactory;

    // Definir la tabla asociada
    protected $table = 'instituciones';

    // Definir la clave primaria
    protected $primaryKey = 'id_institucion';

    // Indicar que la clave primaria no es incremental
    public $incrementing = true;

    // Definir los campos que se pueden asignar masivamente
    protected $fillable = [
        'nombre',
        'direccion',
        'telefono',
        'correo',
    ];

    // Deshabilitar las marcas de tiempo si no las necesitas
    public $timestamps = true;
}