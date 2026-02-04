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
       Schema::table('productos', function (Blueprint $table) {
            $table->string('nombre_producto')->after('modelo');

            $table->string('foto')->nullable()->after('descripcion');

            $table->unsignedTinyInteger('repisas_iluminadas')
                  ->nullable()
                  ->after('foto');

            $table->string('refrigerante')->nullable()
                  ->after('repisas_iluminadas');

            $table->integer('longitud')->nullable()->after('refrigerante');
            $table->integer('profundidad')->nullable()->after('longitud');
            $table->integer('altura')->nullable()->after('profundidad');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn([
                'nombre_producto',
                'foto',
                'repisas_iluminadas',
                'refrigerante',
                'longitud',
                'profundidad',
                'altura',
            ]);
        });
    }
};
