<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagenesAnunciosTable extends Migration
{
    public function up()
    {
        Schema::create('imagenes_anuncios', function (Blueprint $table) {
            $table->id('id_imagen'); // ID autoincremental
            $table->string('ruta'); // ruta del archivo
            $table->string('nombre_img'); // nombre de la imagen
            $table->date('fecha_inicio'); // fecha de inicio
            $table->date('fecha_final'); // fecha final
            $table->integer('duracion_display'); // duración en segundos o similar
            $table->boolean('activa'); // si está activa o no

            $table->timestamps(); // created_at y updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('imagenes_anuncios');
    }
}
