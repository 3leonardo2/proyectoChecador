<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aviso extends Model
{
    use HasFactory;

    protected $fillable = [
        'contenido',
        'fecha_inicio',
        'fecha_fin',
    ];

    // ¡ESTA ES LA LÍNEA IMPORTANTE!
    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
    ];
}