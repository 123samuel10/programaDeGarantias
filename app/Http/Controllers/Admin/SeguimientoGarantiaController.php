<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Garantia;
use App\Models\SeguimientoGarantia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SeguimientoGarantiaController extends Controller
{
    private function validarAdmin(): void
    {
        if (!Auth::check() || Auth::user()->email !== 'admin@gmail.com') {
            abort(403, 'Acceso solo para administradores.');
        }
    }

    public function store(Request $request, Garantia $garantia)
{
    $this->validarAdmin();

    if ($garantia->esFinal()) {
        return back()->with('error', 'No puedes agregar seguimientos a una garantía cerrada o rechazada.');
    }

    $data = $request->validate([
        'estado'  => ['required', Rule::in(['recibida','enrevision','enreparacion','listaparaentregar','cerrada','rechazada'])],
        'nota'    => ['nullable', 'string'],
        'archivo' => ['nullable', 'file', 'max:5120'],

        // NUEVO
        'decision_cobertura' => ['nullable', Rule::in(['cubre','nocubre'])],
        'razon_codigo'       => ['nullable', 'string', 'max:80'],
        'razon_detalle'      => ['nullable', 'string', 'max:2000'],
    ]);

    // Reglas PRO:
    // - Si es "rechazada": decisión obligatoria = nocubre + razón obligatoria
    // - Si es "cerrada": decisión obligatoria + razón obligatoria (de cubre o nocubre)
    if (in_array($data['estado'], ['rechazada','cerrada'], true)) {

        if ($data['estado'] === 'rechazada') {
            $data['decision_cobertura'] = 'nocubre';
        }

        if (empty($data['decision_cobertura'])) {
            return back()->withErrors(['decision_cobertura' => 'Debes indicar si el caso cubre o no cubre garantía.'])->withInput();
        }

        if (empty($data['razon_codigo'])) {
            return back()->withErrors(['razon_codigo' => 'Selecciona un motivo (según política de garantía).'])->withInput();
        }
    } else {
        // Para estados de proceso, limpia para que no ensucie el historial
        $data['decision_cobertura'] = null;
        $data['razon_codigo'] = null;
        $data['razon_detalle'] = null;
    }

    if ($request->hasFile('archivo')) {
        $data['archivo'] = $request->file('archivo')->store('garantias/archivos', 'public');
    }

    $data['garantia_id'] = $garantia->id;

    SeguimientoGarantia::create($data);

    //  Finales
    if (in_array($data['estado'], ['cerrada','rechazada'], true)) {
        $garantia->update(['estado' => $data['estado']]);
        return back()->with('success', 'Seguimiento agregado.');
    }

    $garantia->load('seguimientos');
    $garantia->sincronizarEstadoMacro();

    return back()->with('success', 'Seguimiento agregado.');
}


    public function destroy(SeguimientoGarantia $seguimientoGarantia)
    {
        $this->validarAdmin();

        $garantiaId = $seguimientoGarantia->garantia_id;

        if ($seguimientoGarantia->archivo) {
            Storage::disk('public')->delete($seguimientoGarantia->archivo);
        }

        $seguimientoGarantia->delete();

        //  Recalcular macro del caso padre
        $garantia = Garantia::with('seguimientos')->find($garantiaId);
        if ($garantia && !$garantia->esFinal()) {
            $garantia->sincronizarEstadoMacro();
        }

        return back()->with('success', 'Seguimiento eliminado.');
    }
}
