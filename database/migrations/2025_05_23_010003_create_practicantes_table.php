<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('practicantes', function (Blueprint $table) {
            $table->integer('id_practicante')->primary();
            $table->string('codigo', 50)->unique()->notNullable();
            $table->string('nombre', 100)->notNullable();
            $table->string('apellidos', 100)->notNullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->char('sexo', 1)->nullable();
            $table->string('telefono_contacto', 20)->nullable();
            $table->string('nombre_emergencia', 200)->nullable();
            $table->string('telefono_emergencia', 20)->nullable();
            $table->string('num_seguro', 50)->unique()->nullable();
            $table->integer('institucion_id');
            $table->integer('carrera_id');
            $table->string('nivel_estudios', 100)->nullable();
            $table->string('estado_practicas', 50)->nullable();
            $table->string('area', 100)->nullable();
            $table->date('fecha_inicio')->notNullable();
            $table->date('fecha_final')->nullable();
            $table->time('hora_entrada')->nullable();
            $table->time('hora_salida')->nullable();
            $table->integer('horas_a_cumplir')->nullable();
            $table->integer('horas_cumplidas')->default(0);

            $table->foreign('institucion_id')->references('id_institucion')->on('institucion');
            $table->foreign('carrera_id')->references('id_carrera')->on('carrera');
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('practicantes');
    }
};