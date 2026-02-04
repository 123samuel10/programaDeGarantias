<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1) Agregar columna fecha_entrega_fabrica si no existe
        Schema::table('garantias', function (Blueprint $table) {
            if (!Schema::hasColumn('garantias', 'fecha_entrega_fabrica')) {
                $table->date('fecha_entrega_fabrica')->nullable()->after('numero_serie');
            }
        });

        // 2) LIMPIAR datos huérfanos antes de crear la FK (esto es lo que te faltaba)
        DB::statement("
            UPDATE garantias g
            LEFT JOIN productos p ON p.id = g.producto_id
            SET g.producto_id = NULL
            WHERE g.producto_id IS NOT NULL
              AND p.id IS NULL
        ");

        // 3) Crear la FK (solo si no existe)
        Schema::table('garantias', function (Blueprint $table) {
            //  no uses try/catch aquí para “tapar” errores.
            // Mejor: creamos la FK y si ya existe, Laravel te avisará y lo arreglamos bien.

            $table->foreign('producto_id')
                ->references('id')
                ->on('productos')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('garantias', function (Blueprint $table) {
            // Quitar FK si existe
            try {
                $table->dropForeign(['producto_id']);
            } catch (\Throwable $e) {
                // nada
            }

            // Quitar columna si existe
            if (Schema::hasColumn('garantias', 'fecha_entrega_fabrica')) {
                $table->dropColumn('fecha_entrega_fabrica');
            }
        });
    }
};
