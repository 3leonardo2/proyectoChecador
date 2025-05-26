<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('practicantes_proyectos', function (Blueprint $table) {
            $table->integer('id_practicante');
            $table->integer('id_proyecto');
            $table->date('fecha_asignacion')->default(DB::raw('CURRENT_DATE'))->notNullable();
            $table->date('fecha_finalizacion')->nullable();
            $table->string('rol_en_proyecto', 100)->nullable();

            $table->primary(['id_practicante', 'id_proyecto']); // Clave primaria compuesta
            $table->foreign('id_practicante')->references('id_practicante')->on('practicantes')->onDelete('cascade');
            $table->foreign('id_proyecto')->references('id_proyecto')->on('proyectos')->onDelete('cascade');
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('practicantes_proyectos');
    }
};