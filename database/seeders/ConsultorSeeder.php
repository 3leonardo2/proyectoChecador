<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ConsultorSeeder extends Seeder
{

public function run()
{
    \App\Models\Consultor::create([
        'nombre' => 'admin', // Nombre único para login
        'correo' => null, // Opcional
        'contrasena' => Hash::make('1nt3rc0nt1.'),
    ]);
}
}
