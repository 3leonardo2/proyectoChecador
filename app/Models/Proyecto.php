<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_proyecto'; // Especifica la clave primaria
public $timestamps = false;
    protected $fillable = [
        'nombre_proyecto',
        'descripcion_proyecto',
        'area_proyecto',
    ];

    /**
     * Un proyecto puede tener muchos practicantes.
     */
    public function practicantes()
    {
        return $this->hasMany(Practicante::class, 'proyecto_id', 'id_proyecto');
    }
}