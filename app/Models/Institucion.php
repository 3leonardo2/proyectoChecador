<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // Añade esta importación

class Institucion extends Model
{
    use HasFactory;

    protected $table = 'instituciones';
    protected $primaryKey = 'id_institucion';
    public $incrementing = true;

    protected $fillable = [
        'nombre',
        'acronimo',
        'direccion',
        'telefono',
        'correo',
    ];

    public $timestamps = true;

    /**
     * Relación con las carreras de la institución
     */
    public function carreras(): HasMany
    {
        return $this->hasMany(Carrera::class, 'id_institucion', 'id_institucion');
    }
}