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
    'informe_tecnico',
    'fotos',
    'archivo',

    // ✅ NUEVO (decisión pro)
    'decision_cobertura',
    'razon_codigo',
    'razon_detalle',
];

    protected $casts = [
        'fotos' => 'array',
    ];

    public function garantia()
    {
        return $this->belongsTo(Garantia::class, 'garantia_id');
    }
}
