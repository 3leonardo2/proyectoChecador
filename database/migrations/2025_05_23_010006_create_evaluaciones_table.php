<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('evaluaciones', function (Blueprint $table) {
            $table->increments('id_opinion');
            $table->text('nombre_asesor'); // Aquí almacenaremos solo el nombre
            $table->integer('id_practicante');
            $table->date('fecha_evaluacion')->default(DB::raw('CURRENT_DATE'))->notNullable();
            $table->text('nombre_revision')->nullable();
            $table->text('descripcion_revision')->nullable();
            $table->integer('evaluacion_gral')->nullable(); // Cambiado de text a integer para las estrellas

            $table->foreign('id_practicante')->references('id_practicante')->on('practicantes');

                $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluaciones');
    }
};