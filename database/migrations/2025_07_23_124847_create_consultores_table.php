<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('consultores', function (Blueprint $table) {
        $table->id();
        $table->string('nombre')->unique(); // Añadir unique()
        $table->string('correo')->nullable(); // Hacerlo opcional
        $table->string('contrasena');
        $table->rememberToken();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultores');
    }
};
