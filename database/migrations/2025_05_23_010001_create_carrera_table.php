<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carreras', function (Blueprint $table) {
            $table->id('id_carrera'); // Usar id()
            $table->unsignedBigInteger('id_institucion'); // Clave foránea
            $table->string('nombre_carr', 255);
            $table->string('gerente_carr', 255)->nullable();
            $table->string('tel_gerente', 20)->nullable();
            $table->string('correo_carr', 255)->unique()->nullable();
            
            // La combinación de institución y nombre de carrera debe ser única
            $table->unique(['id_institucion', 'nombre_carr']);

            $table->foreign('id_institucion')->references('id_institucion')->on('instituciones')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carrera');
    }
};