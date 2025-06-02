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
        Schema::create('evaluaciones', function (Blueprint $table) {
            $table->increments('id_opinion'); // SERIAL en PostgreSQL
            $table->integer('id_admin');
            $table->integer('id_practicante');
            $table->date('fecha_evaluacion')->default(DB::raw('CURRENT_DATE'))->notNullable();
            $table->text('nombre_revision')->nullable();
            $table->text('descripcion_revision')->nullable();
            $table->text('evaluacion_gral')->nullable();

            $table->foreign('id_admin')->references('id_admin')->on('administradores');
            $table->foreign('id_practicante')->references('id_practicante')->on('practicantes');
            // $table->timestamps();
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