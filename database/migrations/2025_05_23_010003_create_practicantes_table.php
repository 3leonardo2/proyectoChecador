<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('practicantes', function (Blueprint $table) {
            $table->id('id_practicante'); // Usar id()
            $table->string('codigo', 50)->unique(); // El código generado será único
            $table->string('nombre', 100);
            $table->string('apellidos', 100);
            $table->date('fecha_nacimiento')->nullable();
            $table->string('sexo', 10)->nullable(); // "Hombre", "Mujer", "Otro"
            $table->string('curp', 18)->unique();
            $table->string('direccion', 255)->nullable(); // Añadido desde el form
            $table->string('email_personal', 100)->unique()->nullable(); // Añadido desde el form
            $table->string('telefono_personal', 20)->nullable(); // Renombrado para claridad
            $table->string('nombre_emergencia', 200)->nullable();
            $table->string('telefono_emergencia', 20)->nullable();
            $table->string('num_seguro', 11)->unique()->nullable();
            $table->string('profile_image')->nullable(); // Añadido para la imagen de perfil

            $table->unsignedBigInteger('institucion_id');
            $table->unsignedBigInteger('carrera_id');
            $table->string('email_institucional', 100)->unique()->nullable(); // Añadido desde el form
            $table->string('telefono_institucional', 20)->nullable(); // Añadido desde el form
            $table->string('nivel_estudios', 100)->nullable();

            $table->string('estado_practicas', 50)->default('ACTIVO');
            $table->string('area_asignada', 100)->nullable(); // Renombrado para coincidir
            $table->date('fecha_inicio');
            $table->date('fecha_final')->nullable(); // Renombrado para coincidir
            $table->time('hora_entrada')->nullable();
            $table->time('hora_salida')->nullable();
            $table->integer('horas_requeridas')->nullable(); // Renombrado para coincidir
            $table->integer('horas_registradas')->default(0); // Renombrado para coincidir
            $table->foreign('institucion_id')->references('id_institucion')->on('instituciones');
            $table->foreign('carrera_id')->references('id_carrera')->on('carreras');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('practicantes');
    }
};