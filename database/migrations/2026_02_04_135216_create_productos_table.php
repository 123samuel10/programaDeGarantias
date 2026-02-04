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
     Schema::create('productos', function (Blueprint $table) {
            $table->id();

            $table->string('marca')->nullable();
            $table->string('modelo')->nullable();       // “placa/modelo”
            $table->string('descripcion')->nullable();  // descripción del equipo
            $table->string('tipo_equipo')->nullable();  // vitrina / freezer / etc

            $table->timestamps();
            $table->index(['modelo','marca']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
