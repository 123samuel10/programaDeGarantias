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
      Schema::table('seguimiento_garantias', function (Blueprint $table) {
            if (!Schema::hasColumn('seguimiento_garantias', 'informe_tecnico')) {
                $table->string('informe_tecnico')->nullable()->after('nota');
            }
            if (!Schema::hasColumn('seguimiento_garantias', 'fotos')) {
                $table->json('fotos')->nullable()->after('informe_tecnico');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::table('seguimiento_garantias', function (Blueprint $table) {
            if (Schema::hasColumn('seguimiento_garantias', 'informe_tecnico')) {
                $table->dropColumn('informe_tecnico');
            }
            if (Schema::hasColumn('seguimiento_garantias', 'fotos')) {
                $table->dropColumn('fotos');
            }
        });
    }
};
