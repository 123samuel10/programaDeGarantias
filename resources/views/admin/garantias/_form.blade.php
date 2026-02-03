{{-- resources/views/admin/garantias/_form.blade.php --}}
@php
    $modoEdicion = isset($garantia);
@endphp

<div class="p-6 space-y-6">

    {{-- Cliente + Producto --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="lg:col-span-1">
            <label class="text-sm font-semibold text-gray-700">Cliente *</label>
            <p class="text-xs text-gray-500 mt-1">Selecciona el cliente dueño de la garantía.</p>
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
                <input name="producto_id"
                       value="{{ old('producto_id', $modoEdicion ? $garantia->producto_id : null) }}"
                       class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                       placeholder="ID producto (si aplica)">
                @error('producto_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                <p class="text-xs text-gray-500 mt-1">Luego lo mejoramos a selector por nombre/marca.</p>
            </div>
        </div>
    </div>

    {{-- Serie + Estado --}}
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
                <p class="text-xs text-gray-500 mt-1">Debe ser único (no se repite).</p>
            </div>

            <div>
                <label class="text-sm font-medium text-gray-700">Estado *</label>
                <select name="estado"
                        class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                        required>
                    @foreach(['activa','enproceso','vencida','cerrada','rechazada'] as $e)
                        <option value="{{ $e }}" @selected(old('estado', $modoEdicion ? $garantia->estado : 'activa') === $e)>
                            {{ strtoupper($e) }}
                        </option>
                    @endforeach
                </select>
                @error('estado') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    {{-- Fechas (CAMBIO PRO): compra + vencimiento, meses calculados --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="lg:col-span-1">
            <label class="text-sm font-semibold text-gray-700">Fechas</label>
            <p class="text-xs text-gray-500 mt-1">
                El admin define la fecha de compra y hasta cuándo aplica la garantía.
            </p>
        </div>

        <div class="lg:col-span-2"
             x-data="garantiaFechas()"
             x-init="init()">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Fecha compra --}}
                <div>
                    <label class="text-sm font-medium text-gray-700">Fecha de compra *</label>
                    <input type="date"
                           name="fecha_compra"
                           x-model="fechaCompra"
                           @change="recalcular()"
                           value="{{ old('fecha_compra', $modoEdicion && $garantia->fecha_compra ? $garantia->fecha_compra->format('Y-m-d') : '') }}"
                           class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                           required>
                    @error('fecha_compra') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Vence el --}}
                <div>
                    <label class="text-sm font-medium text-gray-700">Vence el *</label>
                    <input type="date"
                           name="fecha_vencimiento"
                           x-model="fechaVencimiento"
                           @change="recalcular()"
                           value="{{ old('fecha_vencimiento', $modoEdicion && $garantia->fecha_vencimiento ? $garantia->fecha_vencimiento->format('Y-m-d') : '') }}"
                           class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                           required>
                    @error('fecha_vencimiento') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>





        </div>
    </div>

    {{-- Motivo + Notas --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="lg:col-span-1">
            <label class="text-sm font-semibold text-gray-700">Detalle</label>
            <p class="text-xs text-gray-500 mt-1">Motivo y notas internas del caso.</p>
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
                <label class="text-sm font-medium text-gray-700">Notas (opcional)</label>
                <textarea name="notas" rows="4"
                          class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                          placeholder="Observaciones internas…">{{ old('notas', $modoEdicion ? $garantia->notas : null) }}</textarea>
                @error('notas') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

</div>

{{-- Alpine helper (sin librerías extra, solo Alpine que ya usas) --}}
<script>
    function garantiaFechas() {
        return {
            fechaCompra: @json(old('fecha_compra', $modoEdicion && $garantia->fecha_compra ? $garantia->fecha_compra->format('Y-m-d') : null)),
            fechaVencimiento: @json(old('fecha_vencimiento', $modoEdicion && $garantia->fecha_vencimiento ? $garantia->fecha_vencimiento->format('Y-m-d') : null)),
            mesesGarantia: @json(old('meses_garantia', $modoEdicion ? (int)($garantia->meses_garantia ?? 12) : 12)),
            mensaje: '',

            init() {
                this.recalcular();
            },

            sumarMeses(m) {
                if (!this.fechaCompra) {
                    this.mensaje = 'Primero selecciona la fecha de compra.';
                    return;
                }
                const d = new Date(this.fechaCompra + 'T00:00:00');
                d.setMonth(d.getMonth() + m);
                this.fechaVencimiento = d.toISOString().slice(0, 10);
                this.recalcular();
            },

            recalcular() {
                this.mensaje = '';

                if (!this.fechaCompra || !this.fechaVencimiento) return;

                const a = new Date(this.fechaCompra + 'T00:00:00');
                const b = new Date(this.fechaVencimiento + 'T00:00:00');

                if (b < a) {
                    this.mensaje = 'La fecha de vencimiento no puede ser anterior a la compra.';
                    this.mesesGarantia = 0;
                    return;
                }

                const months = (b.getFullYear() - a.getFullYear()) * 12 + (b.getMonth() - a.getMonth());
                this.mesesGarantia = Math.max(1, months || 1);

                this.mensaje = `Garantía calculada: ${this.mesesGarantia} mes(es).`;
            }
        }
    }
</script>
