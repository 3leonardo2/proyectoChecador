<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('instituciones', function (Blueprint $table) {
            $table->id('id_institucion'); // Usar id() para una clave primaria autoincremental
            $table->string('nombre', 255)->unique(); // El nombre de la institución debe ser único
            $table->string('direccion', 255)->nullable();
            $table->string('telefono', 20)->nullable(); // Aumentado por si usan ladas internacionales
            $table->string('correo', 255)->unique()->nullable();
            $table->timestamps(); // Buenas práctica para rastrear registros
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('institucion');
    }
};