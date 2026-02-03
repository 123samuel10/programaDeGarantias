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

        // ✅ No permitir si está FINAL
        if ($garantia->esFinal()) {
            return back()->with('error', 'No puedes agregar seguimientos a una garantía cerrada o rechazada.');
        }

        $data = $request->validate([
            'estado'  => ['required', Rule::in(['recibida','enrevision','enreparacion','listaparaentregar','cerrada','rechazada'])],
            'nota'    => ['nullable', 'string'],
            'archivo' => ['nullable', 'file', 'max:5120'],
        ]);

        if ($request->hasFile('archivo')) {
            $data['archivo'] = $request->file('archivo')->store('garantias/archivos', 'public');
        }

        $data['garantia_id'] = $garantia->id;

        SeguimientoGarantia::create($data);

        // ✅ Si el seguimiento cierra/rechaza -> queda final
        if (in_array($data['estado'], ['cerrada','rechazada'], true)) {
            $garantia->update(['estado' => $data['estado']]);
            return back()->with('success', 'Seguimiento agregado.');
        }

        // ✅ NO forzar enproceso: recalcula (activa/enproceso/vencida) según fecha + seguimientos
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

        // ✅ Recalcular macro del caso padre
        $garantia = Garantia::with('seguimientos')->find($garantiaId);
        if ($garantia && !$garantia->esFinal()) {
            $garantia->sincronizarEstadoMacro();
        }

        return back()->with('success', 'Seguimiento eliminado.');
    }
}
