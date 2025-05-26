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
        Schema::table('practicantes', function (Blueprint $table) {
            // 1. Modificar un campo existente (cambiar longitud de telefono_contacto)
            // Asegúrate de tener doctrine/dbal instalado para usar change()
            $table->string('num_seguro', 11)->change(); // Nueva longitud

            // 2. Agregar un nuevo campo
            $table->string('curp', 18)->unique()->notNullable();

            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('practicantes', function (Blueprint $table) {
            // 1. Revertir la modificación del campo existente
            // Vuelve a la longitud original
            $table->string('num_seguro', 50)->change();

            // 2. Eliminar el nuevo campo agregado
            $table->dropColumn('curp');

        });
    }
};