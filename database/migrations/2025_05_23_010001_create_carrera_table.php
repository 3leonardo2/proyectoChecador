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
        Schema::create('carrera', function (Blueprint $table) {
            $table->integer('id_carrera')->primary(); // Asume que manejas el ID manualmente
            $table->integer('id_institucion'); // Clave foránea
            $table->string('nombre_carr', 255)->notNullable();
            $table->string('gerente_carr', 255)->nullable();
            $table->string('tel_gerente', 20)->nullable();
            $table->string('correo_carr', 255)->unique()->nullable();

            $table->foreign('id_institucion')->references('id_institucion')->on('institucion');
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carrera');
    }
};