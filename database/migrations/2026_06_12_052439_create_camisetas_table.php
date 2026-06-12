<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('camisetas', function (Blueprint $table) {
            $table->id();
            $table->string('titulo'); //
            $table->string('club'); //
            $table->string('pais'); //
            $table->string('tipo'); //
            $table->string('color'); //
            $table->integer('precio'); // Precio base en pesos chilenos
            $table->integer('precio_oferta')->nullable(); // Para lógica de descuentos
            $table->integer('cantidad'); // Control de stock implícito
            $table->text('detalles')->nullable(); //
            $table->string('codigo_producto')->unique(); // SKU único
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('camisetas');
    }
};
