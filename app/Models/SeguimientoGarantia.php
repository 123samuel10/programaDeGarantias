<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SeguimientoGarantia extends Model
{
    use HasFactory;

    protected $table = 'seguimiento_garantias';

    protected $fillable = [
        'garantia_id',
        'estado',
        'nota',

        // NUEVO
        'informe_tecnico',
        'fotos',

        // legado
        'archivo',
    ];

    protected $casts = [
        'fotos' => 'array',
    ];

    public function garantia()
    {
        return $this->belongsTo(Garantia::class, 'garantia_id');
    }
}
