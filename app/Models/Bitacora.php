<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bitacora extends Model
{
    protected $table = 'bitacora';
    protected $primaryKey = 'id_evento';
    public $timestamps = false;

    protected $fillable = [
        'practicante_id',
        'fecha',
        'hora',
        'tipo',
        'descripcion'
    ];

    // Relación con practicante
    public function practicante(): BelongsTo
    {
        return $this->belongsTo(Practicante::class, 'practicante_id', 'id_practicante');
    }
}