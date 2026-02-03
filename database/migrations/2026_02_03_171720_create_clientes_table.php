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
        Schema::create('clientes', function (Blueprint $table) {
      $table->id();

            // Tipo
            $table->enum('tipo_cliente', ['persona','empresa'])->default('persona');

            // Identidad
            $table->string('nombre_contacto');
            $table->string('empresa')->nullable();
            $table->string('documento')->nullable(); // CC / NIT

            // Contacto
            $table->string('email')->nullable();
            $table->string('telefono')->nullable();
            $table->string('telefono_alterno')->nullable();

            // Ubicación
            $table->string('pais')->nullable();
            $table->string('ciudad')->nullable();
            $table->string('direccion')->nullable();

            // Notas internas
            $table->text('notas')->nullable();

            $table->timestamps();

            $table->index(['tipo_cliente','email']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
