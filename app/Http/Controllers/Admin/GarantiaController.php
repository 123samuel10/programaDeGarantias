<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Garantia;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class GarantiaController extends Controller
{
    private function validarAdmin(): void
    {
        if (!Auth::check() || Auth::user()->email !== 'admin@gmail.com') {
            abort(403, 'Acceso solo para administradores.');
        }
    }

    /**
     * ✅ AUTO-VENCIMIENTO SIN CRON / SIN MIDDLEWARE
     * Se ejecuta cuando el admin entra a index/show/edit/update.
     */
    private function autoVencerGarantias(): void
    {
        Garantia::whereNotIn('estado', ['cerrada', 'rechazada'])
            ->whereNotNull('fecha_vencimiento')
            ->whereDate('fecha_vencimiento', '<', now()->toDateString())
            ->update(['estado' => 'vencida']);
    }

    public function index(Request $request)
    {
        $this->validarAdmin();
        $this->autoVencerGarantias();

        $q = Garantia::query()->with('cliente');

        if ($buscar = $request->get('buscar')) {
            $buscar = addcslashes($buscar, '%_');

            $q->where(function ($qq) use ($buscar) {
                $qq->where('numero_serie', 'like', "%{$buscar}%")
                  ->orWhere('estado', 'like', "%{$buscar}%")
                  ->orWhereHas('cliente', function ($c) use ($buscar) {
                      $c->where('nombre_contacto', 'like', "%{$buscar}%")
                        ->orWhere('empresa', 'like', "%{$buscar}%")
                        ->orWhere('email', 'like', "%{$buscar}%")
                        ->orWhere('documento', 'like', "%{$buscar}%");
                  });
            });
        }

        if ($estado = $request->get('estado')) {
            $q->where('estado', $estado);
        }

        $garantias = $q->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.garantias.index', compact('garantias'));
    }

    public function create()
    {
        $this->validarAdmin();

        $clientes = Cliente::orderBy('nombre_contacto')->get();
        return view('admin.garantias.create', compact('clientes'));
    }

    public function store(Request $request)
    {
        $this->validarAdmin();

        $data = $request->validate([
            'cliente_id'        => ['required', 'exists:clientes,id'],
            'producto_id'       => ['nullable', 'integer'],
            'numero_serie'      => ['required', 'string', 'max:255', 'unique:garantias,numero_serie'],
            'fecha_compra'      => ['required', 'date'],
            'fecha_vencimiento' => ['required', 'date', 'after_or_equal:fecha_compra'],
            'motivo'            => ['nullable', 'string', 'max:255'],
            'notas'             => ['nullable', 'string'],
        ]);

        $compra = Carbon::parse($data['fecha_compra'])->startOfDay();
        $vence  = Carbon::parse($data['fecha_vencimiento'])->startOfDay();

        $data['meses_garantia']    = max(1, $compra->diffInMonths($vence));
        $data['fecha_vencimiento'] = $vence->toDateString();

        // ✅ Si ya pasó la fecha al guardar -> VENCIDA, si no -> ACTIVA
        $data['estado'] = now()->startOfDay()->gt($vence) ? 'vencida' : 'activa';

        $g = Garantia::create($data);

        return redirect()
            ->route('admin.garantias.show', $g)
            ->with('success', 'Garantía registrada correctamente.');
    }

    public function show(Garantia $garantia)
    {
        $this->validarAdmin();
        $this->autoVencerGarantias();

        $garantia->load(['cliente', 'seguimientos']);

        // ✅ sincroniza estado macro (activa/enproceso/vencida)
        $garantia->sincronizarEstadoMacro();

        return view('admin.garantias.show', compact('garantia'));
    }

    public function edit(Garantia $garantia)
    {
        $this->validarAdmin();
        $this->autoVencerGarantias();

        // ✅ por si entras a editar y ya venció
        $garantia->sincronizarEstadoMacro();

        $clientes = Cliente::orderBy('nombre_contacto')->get();
        return view('admin.garantias.edit', compact('garantia', 'clientes'));
    }

    public function update(Request $request, Garantia $garantia)
    {
        $this->validarAdmin();

        $data = $request->validate([
            'cliente_id'        => ['required', 'exists:clientes,id'],
            'producto_id'       => ['nullable', 'integer'],
            'numero_serie'      => ['required', 'string', 'max:255', Rule::unique('garantias', 'numero_serie')->ignore($garantia->id)],
            'fecha_compra'      => ['required', 'date'],
            'fecha_vencimiento' => ['required', 'date', 'after_or_equal:fecha_compra'],
            'motivo'            => ['nullable', 'string', 'max:255'],
            'notas'             => ['nullable', 'string'],
        ]);

        $compra = Carbon::parse($data['fecha_compra'])->startOfDay();
        $vence  = Carbon::parse($data['fecha_vencimiento'])->startOfDay();

        $data['meses_garantia']    = max(1, $compra->diffInMonths($vence));
        $data['fecha_vencimiento'] = $vence->toDateString();

        $garantia->update($data);

        // ✅ asegura auto vencimiento global
        $this->autoVencerGarantias();

        // ✅ recalcula macro si NO es final
        if (!$garantia->esFinal()) {
            $garantia->load('seguimientos');
            $garantia->sincronizarEstadoMacro();
        }

        return back()->with('success', 'Garantía actualizada.');
    }

    public function destroy(Garantia $garantia)
    {
        $this->validarAdmin();

        $garantia->delete();

        return redirect()
            ->route('admin.garantias.index')
            ->with('success', 'Garantía eliminada.');
    }
}
