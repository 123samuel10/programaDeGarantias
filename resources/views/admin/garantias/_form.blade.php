{{-- resources/views/admin/garantias/_form.blade.php --}}
@php
    $modoEdicion = isset($garantia);

    $estadoActual = old('estado', $modoEdicion ? ($garantia->estado ?? 'activa') : 'activa');

    $estadoLabels = [
        'activa' => 'Activa',
        'enproceso' => 'En proceso',
        'vencida' => 'Vencida',
        'cerrada' => 'Cerrada',
        'rechazada' => 'Rechazada',
    ];

    $estadoBadge = [
        'activa' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
        'enproceso' => 'bg-blue-50 text-blue-700 border-blue-100',
        'vencida' => 'bg-amber-50 text-amber-800 border-amber-100',
        'cerrada' => 'bg-gray-100 text-gray-700 border-gray-200',
        'rechazada' => 'bg-red-50 text-red-700 border-red-100',
    ][$estadoActual] ?? 'bg-gray-100 text-gray-700 border-gray-200';
@endphp

<div class="p-6 space-y-6">

    {{-- Cliente + Producto --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="lg:col-span-1">
            <label class="text-sm font-semibold text-gray-700">Cliente *</label>
            <p class="text-xs text-gray-500 mt-1">Selecciona el titular de la garantía.</p>
        </div>

        <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-medium text-gray-700">Cliente</label>
                <select name="cliente_id"
                        class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                        required>
                    <option value="">— Selecciona —</option>
                    @foreach($clientes as $c)
                        @php
                            $label = $c->nombre_contacto;
                            $sub = $c->empresa ? ' · '.$c->empresa : ' · Persona natural';
                        @endphp
                        <option value="{{ $c->id }}"
                            @selected(old('cliente_id', $modoEdicion ? $garantia->cliente_id : null) == $c->id)>
                            {{ $label }}{{ $sub }}
                        </option>
                    @endforeach
                </select>
                @error('cliente_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="text-sm font-medium text-gray-700">Producto (opcional)</label>
                <select name="producto_id"
                        class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                    <option value="">— Sin producto —</option>
                    @foreach($productos as $p)
                        <option value="{{ $p->id }}"
                            @selected(old('producto_id', $modoEdicion ? $garantia->producto_id : null) == $p->id)>
                            {{ ($p->modelo ?: 'Sin modelo') }} {{ $p->marca ? '· '.$p->marca : '' }}
                        </option>
                    @endforeach
                </select>
                @error('producto_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                <p class="text-xs text-gray-500 mt-1">Según documento: placa/modelo y descripción ayudan al diagnóstico.</p>
            </div>
        </div>
    </div>

    {{-- Serie + Estado (solo lectura) --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="lg:col-span-1">
            <label class="text-sm font-semibold text-gray-700">Identificación</label>
            <p class="text-xs text-gray-500 mt-1">Control del equipo y estado del caso.</p>
        </div>

        <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-medium text-gray-700">Número de serie *</label>
                <input name="numero_serie"
                       value="{{ old('numero_serie', $modoEdicion ? $garantia->numero_serie : null) }}"
                       class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                       placeholder="Ej: SN-REF-000123"
                       required>
                @error('numero_serie') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="text-sm font-medium text-gray-700">Estado (auto)</label>

                <div class="mt-1 w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border {{ $estadoBadge }}">
                        {{ $estadoLabels[$estadoActual] ?? strtoupper($estadoActual) }}
                    </span>
                    <p class="text-xs text-gray-500 mt-2">
                        El estado se ajusta automáticamente por vencimiento y seguimientos (cerrada / rechazada son finales).
                    </p>
                </div>

                <input type="hidden" name="estado" value="{{ $estadoActual }}">
                @error('estado') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    {{-- Fecha entrega fábrica + vencimiento calculado --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4"
         x-data="garantiaNormaFechas()"
         x-init="init()">
        <div class="lg:col-span-1">
            <label class="text-sm font-semibold text-gray-700">Fechas (norma)</label>
            <p class="text-xs text-gray-500 mt-1">La garantía es de 18 meses desde entrega en fábrica.</p>
        </div>

        <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-medium text-gray-700">Entrega en fábrica *</label>
                <input type="date"
                       name="fecha_entrega_fabrica"
                       x-model="entrega"
                       @change="recalcular()"
                       value="{{ old('fecha_entrega_fabrica', $modoEdicion && $garantia->fecha_entrega_fabrica ? $garantia->fecha_entrega_fabrica->format('Y-m-d') : '') }}"
                       class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                       required>
                @error('fecha_entrega_fabrica') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="text-sm font-medium text-gray-700">Vencimiento (auto)</label>
                <input type="text"
                       x-model="vence"
                       readonly
                       class="mt-1 w-full rounded-xl border-gray-200 bg-gray-50 text-gray-900">
                <p class="text-xs text-gray-500 mt-1" x-text="mensaje"></p>
            </div>
        </div>
    </div>

    {{-- Motivo + Notas --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="lg:col-span-1">
            <label class="text-sm font-semibold text-gray-700">Detalle</label>
            <p class="text-xs text-gray-500 mt-1">Descripción general del caso.</p>
        </div>

        <div class="lg:col-span-2 space-y-4">
            <div>
                <label class="text-sm font-medium text-gray-700">Motivo (opcional)</label>
                <input name="motivo"
                       value="{{ old('motivo', $modoEdicion ? $garantia->motivo : null) }}"
                       class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                       placeholder="Ej: Falla de compresor, fuga, ruido anormal…">
                @error('motivo') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="text-sm font-medium text-gray-700">Notas internas (opcional)</label>
                <textarea name="notas" rows="4"
                          class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                          placeholder="Observaciones internas…">{{ old('notas', $modoEdicion ? $garantia->notas : null) }}</textarea>
                @error('notas') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

</div>

<script>
    function garantiaNormaFechas() {
        return {
            entrega: @json(old('fecha_entrega_fabrica', $modoEdicion && $garantia->fecha_entrega_fabrica ? $garantia->fecha_entrega_fabrica->format('Y-m-d') : null)),
            vence: '',
            mensaje: '',

            init() { this.recalcular(); },

            addMonthsNoOverflow(dateStr, months) {
                const [y, m, d] = dateStr.split('-').map(Number);
                const targetMonth = (m - 1) + months;

                const ny = y + Math.floor(targetMonth / 12);
                const nm = (targetMonth % 12 + 12) % 12;

                const lastDay = new Date(ny, nm + 1, 0).getDate();
                const nd = Math.min(d, lastDay);

                const out = new Date(ny, nm, nd);
                const yyyy = out.getFullYear();
                const mm = String(out.getMonth() + 1).padStart(2, '0');
                const dd = String(out.getDate()).padStart(2, '0');
                return `${yyyy}-${mm}-${dd}`;
            },

            recalcular() {
                this.mensaje = '';
                if (!this.entrega) { this.vence = ''; return; }

                this.vence = this.addMonthsNoOverflow(this.entrega, 18);
                this.mensaje = 'Garantía estándar: 18 meses desde entrega en fábrica.';
            }
        }
    }
</script>
