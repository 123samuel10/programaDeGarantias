<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('seguimiento_garantias', function (Blueprint $table) {
            if (!Schema::hasColumn('seguimiento_garantias', 'decision_cobertura')) {
                $table->enum('decision_cobertura', ['cubre','nocubre'])
                      ->nullable()
                      ->after('estado');
            }

            if (!Schema::hasColumn('seguimiento_garantias', 'razon_codigo')) {
                $table->string('razon_codigo', 80)->nullable()->after('decision_cobertura');
            }

            if (!Schema::hasColumn('seguimiento_garantias', 'razon_detalle')) {
                $table->text('razon_detalle')->nullable()->after('razon_codigo');
            }
        });
    }

    public function down(): void
    {
        Schema::table('seguimiento_garantias', function (Blueprint $table) {
            if (Schema::hasColumn('seguimiento_garantias', 'decision_cobertura')) $table->dropColumn('decision_cobertura');
            if (Schema::hasColumn('seguimiento_garantias', 'razon_codigo')) $table->dropColumn('razon_codigo');
            if (Schema::hasColumn('seguimiento_garantias', 'razon_detalle')) $table->dropColumn('razon_detalle');
        });
    }
};
