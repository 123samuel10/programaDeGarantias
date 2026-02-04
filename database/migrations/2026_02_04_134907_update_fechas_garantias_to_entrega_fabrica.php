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
     Schema::table('garantias', function (Blueprint $table) {
            $table->date('fecha_entrega_fabrica')->nullable()->after('numero_serie');
        });

        // Opcional: mantener fecha_compra pero ya no usarla
        // o renombrarla si quieres eliminarla después.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('garantias', function (Blueprint $table) {
            $table->dropColumn('fecha_entrega_fabrica');
        });
    }
};
