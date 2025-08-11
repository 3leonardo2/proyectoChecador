<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Consultor extends Authenticatable
{
    use Notifiable;

    protected $table = 'consultores';
    protected $fillable = ['nombre', 'correo', 'contrasena'];
    protected $hidden = ['contrasena', 'remember_token'];

    public function getAuthPassword()
    {
        return $this->contrasena;
    }
}