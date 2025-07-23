<?php


use Illuminate\Support\Facades\DB;
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
    // Añadir columna para id_practicante
    Schema::table('bitacora', function (Blueprint $table) {
        $table->unsignedBigInteger('practicante_id')->nullable()->after('clave_prac');
    });

    // Poblar la nueva columna
    DB::statement('UPDATE bitacora b SET practicante_id = (SELECT id_practicante FROM practicantes p WHERE p.codigo = b.clave_prac)');

    // Eliminar la antigua restricción
    Schema::table('bitacora', function (Blueprint $table) {
        $table->dropForeign(['clave_prac']);
        $table->dropColumn('clave_prac');
    });

    // Añadir nueva restricción
    Schema::table('bitacora', function (Blueprint $table) {
        $table->foreign('practicante_id')
              ->references('id_practicante')->on('practicantes')
              ->onDelete('restrict');
    });
}
};
