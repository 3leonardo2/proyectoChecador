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
        Schema::create('institucion', function (Blueprint $table) {
            $table->integer('id_institucion')->primary(); // Asume que manejas el ID manualmente
            $table->string('nombre', 255)->notNullable();
            $table->string('direccion', 255)->nullable();
            $table->string('telefono', 10)->nullable();
            $table->string('correo', 255)->unique()->nullable();
            // $table->timestamps(); // Opcional: si quieres created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('institucion');
    }
};