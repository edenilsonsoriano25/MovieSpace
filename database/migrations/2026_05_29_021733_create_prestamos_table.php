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
        Schema::create('prestamos', function (Blueprint $table) {
            $table->id();
            // Llaves foráneas apuntando a la tabla users
            $table->foreignId('id_usuario')->constrained('users')->onDelete('restrict');
            $table->foreignId('id_trabajador')->constrained('users')->onDelete('restrict');

            $table->dateTime('fecha_salida');
            $table->dateTime('fecha_limite');
            $table->dateTime('fecha_entrega_real')->nullable(); // Nulo hasta que se devuelva
            $table->enum('estado_prestamo', ['activo', 'devuelto', 'vencido'])->default('activo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestamos');
    }
};
