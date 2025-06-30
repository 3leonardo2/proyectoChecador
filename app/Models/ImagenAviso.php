<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
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
    ];

    // ¡ESTA ES LA LÍNEA IMPORTANTE!
    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
    ];

    public function deleteImageFile()
{
    if (!$this->nombre_archivo) return false;
    
    $path = 'imagenes_avisos/' . $this->nombre_archivo;
    
    if (Storage::disk('public')->exists($path)) {
        return Storage::disk('public')->delete($path);
    }
    
    return false;
}
}