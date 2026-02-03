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
        Schema::create('seguimiento_garantias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('garantia_id')->constrained('garantias')->cascadeOnDelete();

            $table->enum('estado', [
                'recibida','enrevision','enreparacion','listaparaentregar','cerrada','rechazada'
            ])->default('recibida');

            $table->text('nota')->nullable();
            $table->string('archivo')->nullable();
            $table->timestamps();

            $table->index(['garantia_id','estado']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seguimiento_garantias');
    }
};
