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
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_prestamo')->constrained('prestamos')->onDelete('restrict');
            $table->foreignId('id_trabajador')->constrained('users')->onDelete('restrict');
            $table->decimal('monto', 8, 2);
            $table->enum('concepto', ['alquiler', 'multa']);
            $table->enum('metodo_pago', ['efectivo', 'tarjeta', 'transferencia']);
            $table->dateTime('fecha_pago');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
