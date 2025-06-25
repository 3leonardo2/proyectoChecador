<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImagenAviso extends Model
{
    use HasFactory;

    protected $fillable = [
        'ruta',
        'nombre_archivo',
        'titulo',
        'descripcion',
        'fecha_inicio',
        'fecha_fin',
        'duracion',
        'activo',
        'user_id',
    ];

    // ¡ESTA ES LA LÍNEA IMPORTANTE!
    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
    ];
}