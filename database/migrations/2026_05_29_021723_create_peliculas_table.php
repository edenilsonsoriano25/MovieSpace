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
        Schema::create('peliculas', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('sinopsis')->nullable();
            $table->string('genero');
            $table->integer('anio');
            $table->string('portada')->nullable(); // Guardará la ruta de la imagen
            $table->decimal('precio_alquiler', 8, 2);
            $table->integer('copias_totales');
            $table->integer('copias_en_estante');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peliculas');
    }
};
