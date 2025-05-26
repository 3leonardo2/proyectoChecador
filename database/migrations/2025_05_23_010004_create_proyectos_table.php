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
        Schema::create('proyectos', function (Blueprint $table) {
            $table->increments('id_proyecto'); // SERIAL en PostgreSQL, int autoincremental
            $table->string('nombre_proyecto', 255)->notNullable();
            $table->text('descripcion')->nullable();
            $table->date('fecha_inicio_proyecto')->notNullable();
            $table->date('fecha_fin_estimada')->nullable();
            $table->string('estado_proyecto', 50)->default('Activo');
            $table->integer('id_lider_proyecto')->nullable(); // Clave foránea opcional
            $table->string('area_proyecto', 100)->nullable();
            $table->text('tecnologias_usadas')->nullable();

            $table->foreign('id_lider_proyecto')->references('id_admin')->on('administradores')->onDelete('set null'); // Si el líder se elimina, este campo se pone a NULL
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proyectos');
    }
};