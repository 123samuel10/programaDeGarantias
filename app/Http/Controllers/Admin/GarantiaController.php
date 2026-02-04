<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Garantia;
use App\Models\Cliente;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class GarantiaController extends Controller
{
    private function validarAdmin(): void
    {
        // ✅ Mantengo tu forma actual
        if (!Auth::check() || Auth::user()->email !== 'admin@gmail.com') {
            abort(403, 'Acceso solo para administradores.');
        }
    }

    /**
     * ✅ Auto-vencer en cada entrada al módulo (sin cron).
     */
    private function autoVencerGarantias(): void
    {
        Garantia::whereNotIn('estado', ['cerrada', 'rechazada', 'vencida'])
            ->whereNotNull('fecha_vencimiento')
            ->whereDate('fecha_vencimiento', '<', now()->toDateString())
            ->update(['estado' => 'vencida']);
    }

    /**
     * ✅ Norma: vencimiento = entrega_fabrica + 18 meses
     */
    private function calcularNorma18m(string $fechaEntregaFabrica): array
    {
        $entrega = Carbon::parse($fechaEntregaFabrica)->startOfDay();
        $vence = (clone $entrega)->addMonthsNoOverflow(18)->startOfDay();

        return [
            'meses_garantia' => 18,
            'fecha_vencimiento' => $vence->toDateString(),
        ];
    }

    // ==========================
    // INDEX
    // ==========================
    public function index(Request $request)
    {
        $this->validarAdmin();
        $this->autoVencerGarantias();

        $q = Garantia::query()->with(['cliente', 'producto']);

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
          })

          // ✅ NUEVO: buscar por producto
          ->orWhereHas('producto', function ($p) use ($buscar) {
              $p->where('nombre_producto', 'like', "%{$buscar}%")
                ->orWhere('marca', 'like', "%{$buscar}%")
                ->orWhere('modelo', 'like', "%{$buscar}%")
                ->orWhere('descripcion', 'like', "%{$buscar}%")
                ->orWhere('tipo_equipo', 'like', "%{$buscar}%");
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

    // ==========================
    // CREATE
    // ==========================
    public function create()
    {
        $this->validarAdmin();

        $clientes = Cliente::orderBy('nombre_contacto')->get();
        $productos = Producto::orderBy('id')->get(); // si aún no tienes campos, igual sirve

        return view('admin.garantias.create', compact('clientes', 'productos'));
    }

    // ==========================
    // STORE
    // ==========================
    public function store(Request $request)
    {
        $this->validarAdmin();

        $data = $request->validate([
            'cliente_id' => ['required', 'exists:clientes,id'],
            'producto_id' => ['nullable', 'exists:productos,id'],
            'numero_serie' => ['required', 'string', 'max:255', 'unique:garantias,numero_serie'],

            // ✅ Norma
            'fecha_entrega_fabrica' => ['required', 'date'],

            'motivo' => ['nullable', 'string', 'max:255'],
            'notas' => ['nullable', 'string'],
        ]);

        // ✅ aplica norma 18 meses
        $norma = $this->calcularNorma18m($data['fecha_entrega_fabrica']);
        $data['meses_garantia'] = $norma['meses_garantia'];
        $data['fecha_vencimiento'] = $norma['fecha_vencimiento'];

        // ✅ estado inicial: activa o vencida (si entregaron hace más de 18m)
        $vence = Carbon::parse($data['fecha_vencimiento'])->startOfDay();
        $data['estado'] = now()->startOfDay()->gt($vence) ? 'vencida' : 'activa';

        $g = Garantia::create($data);

        return redirect()
            ->route('admin.garantias.show', $g)
            ->with('success', 'Garantía registrada correctamente (18 meses desde entrega en fábrica).');
    }

    // ==========================
    // SHOW
    // ==========================
    public function show(Garantia $garantia)
    {
        $this->validarAdmin();
        $this->autoVencerGarantias();

        $garantia->load(['cliente', 'producto', 'seguimientos']);

        if (method_exists($garantia, 'sincronizarEstadoMacro')) {
            $garantia->sincronizarEstadoMacro();
        }

        return view('admin.garantias.show', compact('garantia'));
    }

    // ==========================
    // EDIT
    // ==========================
    public function edit(Garantia $garantia)
    {
        $this->validarAdmin();
        $this->autoVencerGarantias();

        $clientes = Cliente::orderBy('nombre_contacto')->get();
        $productos = Producto::orderBy('id')->get();

        if (method_exists($garantia, 'sincronizarEstadoMacro')) {
            $garantia->sincronizarEstadoMacro();
        }

        return view('admin.garantias.edit', compact('garantia', 'clientes', 'productos'));
    }

    // ==========================
    // UPDATE
    // ==========================
    public function update(Request $request, Garantia $garantia)
    {
        $this->validarAdmin();

        $data = $request->validate([
            'cliente_id' => ['required', 'exists:clientes,id'],
            'producto_id' => ['nullable', 'exists:productos,id'],
            'numero_serie' => [
                'required', 'string', 'max:255',
                Rule::unique('garantias', 'numero_serie')->ignore($garantia->id),
            ],

            // ✅ Norma
            'fecha_entrega_fabrica' => ['required', 'date'],

            'motivo' => ['nullable', 'string', 'max:255'],
            'notas' => ['nullable', 'string'],
        ]);

        // ✅ recalcula norma 18 meses SIEMPRE que cambie la entrega
        $norma = $this->calcularNorma18m($data['fecha_entrega_fabrica']);
        $data['meses_garantia'] = $norma['meses_garantia'];
        $data['fecha_vencimiento'] = $norma['fecha_vencimiento'];

        $garantia->update($data);

        // ✅ auto-vencer global
        $this->autoVencerGarantias();

        // ✅ recalcula estado macro si no es final
        if (method_exists($garantia, 'esFinal') && method_exists($garantia, 'sincronizarEstadoMacro')) {
            $garantia->load('seguimientos');
            if (!$garantia->esFinal()) {
                $garantia->sincronizarEstadoMacro();
            }
        }

        return back()->with('success', 'Garantía actualizada (norma 18 meses aplicada).');
    }

    // ==========================
    // DESTROY
    // ==========================
    public function destroy(Garantia $garantia)
    {
        $this->validarAdmin();

        $garantia->delete();

        return redirect()
            ->route('admin.garantias.index')
            ->with('success', 'Garantía eliminada.');
    }
}
