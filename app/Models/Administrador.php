<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Administrador extends Authenticatable
{
    public $timestamps = false;
    protected $table = 'administradores';
    protected $primaryKey = 'id_admin';
    protected $fillable = ['nombre', 'correo', 'contrasena', 'departamento','rol'];
    
    protected $hidden = [
        'contrasena',
        'remember_token',
    ];
    
    // Cambiar nombre del campo de contraseña
    public function getAuthPassword()
    {
        return $this->contrasena;
    }
}