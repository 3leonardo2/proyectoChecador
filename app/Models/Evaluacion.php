<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Evaluacion extends Model
{
    protected $table = 'evaluaciones';
    protected $primaryKey = 'id_opinion';
    
    protected $fillable = [
        'nombre_asesor',
        'id_practicante',
        'nombre_revision',
        'descripcion_revision',
        'evaluacion_gral'
    ];

    /**
     * Obtiene el practicante asociado a la evaluación
     */
    public function practicante(): BelongsTo
    {
        return $this->belongsTo(Practicante::class, 'id_practicante');
    }
}