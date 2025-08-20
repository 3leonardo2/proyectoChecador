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
        Schema::create('bitacora', function (Blueprint $table) {
            $table->increments('id_evento'); // SERIAL en PostgreSQL
            $table->unsignedBigInteger('practicante_id'); 
            // Si refencia el código del practicante
            
            // ALTERNATIVA si refencia el ID numérico: $table->integer('id_practicante')->notNullable();
            $table->date('fecha')->default(DB::raw('CURRENT_DATE'))->notNullable();
            $table->time('hora')->default(DB::raw('CURRENT_TIME'))->notNullable();
            $table->string('tipo', 50)->notNullable();
            $table->text('descripcion')->nullable();

            $table->foreign('practicante_id')->references('id_practicante')->on('practicantes');
            // ALTERNATIVA: $table->foreign('id_practicante')->references('id_practicante')->on('practicantes');
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bitacora');
    }
};