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
        Schema::create('administradores', function (Blueprint $table) {
            $table->bigIncrements('id_admin');
            $table->string('nombre', 100)->notNullable();
            $table->string('correo', 255)->unique()->notNullable();
            $table->string('contrasena', 255)->notNullable(); // Almacenar hash de contraseña
            $table->string('departamento', 100)->nullable();
            $table->enum('rol', ['rh', 'asesor'])->notNullable();
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('administradores');
    }
};