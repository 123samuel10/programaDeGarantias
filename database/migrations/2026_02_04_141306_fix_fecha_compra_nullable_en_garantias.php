<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('garantias', function (Blueprint $table) {
            // si existe fecha_compra, la volvemos nullable
            if (Schema::hasColumn('garantias', 'fecha_compra')) {
                $table->date('fecha_compra')->nullable()->change();
            }

            // asegurar campo norma por si acaso
            if (!Schema::hasColumn('garantias', 'fecha_entrega_fabrica')) {
                $table->date('fecha_entrega_fabrica')->nullable()->after('numero_serie');
            }
        });

        // si tienes registros viejos, copia fecha_compra -> fecha_entrega_fabrica cuando esté null
        try {
            DB::statement("
                UPDATE garantias
                SET fecha_entrega_fabrica = fecha_compra
                WHERE fecha_entrega_fabrica IS NULL AND fecha_compra IS NOT NULL
            ");
        } catch (\Throwable $e) {}
    }

    public function down(): void
    {
        Schema::table('garantias', function (Blueprint $table) {
            if (Schema::hasColumn('garantias', 'fecha_compra')) {
                $table->date('fecha_compra')->nullable(false)->change();
            }
        });
    }
};
