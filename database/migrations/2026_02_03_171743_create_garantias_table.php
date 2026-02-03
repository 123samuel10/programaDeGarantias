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
        Schema::create('garantias', function (Blueprint $table) {
         $table->id();

            $table->foreignId('cliente_id')->constrained('clientes')->cascadeOnDelete();
         $table->unsignedBigInteger('producto_id')->nullable(); // por ahora sin llave foránea


            $table->string('numero_serie')->unique();
            $table->date('fecha_compra');
            $table->unsignedInteger('meses_garantia')->default(12);
            $table->date('fecha_vencimiento');

            $table->enum('estado', ['activa','enproceso','vencida','cerrada','rechazada'])->default('activa');

            $table->string('motivo')->nullable();
            $table->text('notas')->nullable();

            $table->timestamps();

            $table->index(['cliente_id','producto_id','estado']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('garantias');
    }
};
