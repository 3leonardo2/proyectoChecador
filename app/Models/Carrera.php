<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Importación añadida

class Carrera extends Model
{
    use HasFactory;

    protected $table = 'carreras';
    protected $primaryKey = 'id_carrera';
    public $incrementing = true;

    protected $fillable = [
        'nombre_carr',
        'gerente_carr',
        'tel_gerente',
        'correo_carr',
        'id_institucion',
    ];

    public $timestamps = true;

    /**
     * Relación con la institución
     */
    public function institucion(): BelongsTo
    {
        return $this->belongsTo(Institucion::class, 'id_institucion', 'id_institucion');
    }
}