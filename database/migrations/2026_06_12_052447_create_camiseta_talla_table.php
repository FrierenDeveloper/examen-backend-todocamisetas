<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('camiseta_talla', function (Blueprint $table) {
            $table->id();
            $table->foreignId('camiseta_id')->constrained('camisetas')->onDelete('cascade'); // Restricción de integridad y cascada
            $table->foreignId('talla_id')->constrained('tallas')->onDelete('cascade'); // Restricción de integridad y cascada
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('camiseta_talla');
    }
};
