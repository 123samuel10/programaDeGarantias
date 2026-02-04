{{-- resources/views/admin/garantias/show.blade.php --}}
<x-app-layout>
@php
    /**
     * ==========================================================
     * Datos base
     * ==========================================================
     */
    $c = $garantia->cliente;
    $estado = $garantia->estado ?? 'activa';
    $esFinal = in_array($estado, ['cerrada','rechazada'], true);

    $estadoLabels = [
        'activa'     => 'Activa',
        'enproceso'  => 'En proceso',
        'vencida'    => 'Vencida',
        'cerrada'    => 'Cerrada',
        'rechazada'  => 'Rechazada',
    ];

    $estadoBadge = [
        'activa'     => 'bg-emerald-50 text-emerald-700 border-emerald-100',
        'enproceso'  => 'bg-blue-50 text-blue-700 border-blue-100',
        'vencida'    => 'bg-amber-50 text-amber-800 border-amber-100',
        'cerrada'    => 'bg-gray-100 text-gray-700 border-gray-200',
        'rechazada'  => 'bg-red-50 text-red-700 border-red-100',
    ][$estado] ?? 'bg-gray-100 text-gray-700 border-gray-200';

    $seguimientoLabels = [
        'recibida'          => 'Recibida',
        'enrevision'        => 'En revisión',
        'enreparacion'      => 'En reparación',
        'listaparaentregar' => 'Lista para entregar',
        'cerrada'           => 'Cerrada',
        'rechazada'         => 'Rechazada',
    ];

    $vence = $garantia->fecha_vencimiento;
    $dias  = $vence ? now()->startOfDay()->diffInDays($vence->startOfDay(), false) : null;

    if ($dias !== null) {
        if ($dias > 0) $textoDias = "Vence en {$dias} días";
        elseif ($dias === 0) $textoDias = "Vence hoy";
        else $textoDias = "Venció hace ".abs($dias)." días";
    } else {
        $textoDias = null;
    }

    $seguimientoBadge = [
        'recibida'          => 'bg-blue-50 text-blue-700 border-blue-100',
        'enrevision'        => 'bg-amber-50 text-amber-800 border-amber-100',
        'enreparacion'      => 'bg-indigo-50 text-indigo-700 border-indigo-100',
        'listaparaentregar' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
        'cerrada'           => 'bg-gray-100 text-gray-700 border-gray-200',
        'rechazada'         => 'bg-red-50 text-red-700 border-red-100',
    ];

    /**
     * ==========================================================
     * Catálogo de motivos (según tu documento)
     * - "SÍ CUBRE" = defectos de ensamble/fabricación
     * - "NO CUBRE" = exclusiones del documento
     * ==========================================================
     */
    $razonesCubre = [
        'Defectos cubiertos (según garantía)' => [
            'defecto_materiales_mano_obra' => 'Defecto de fabricación: materiales / mano de obra',
            'defecto_ensamble'             => 'Defecto de ensamble',
            'mecanico_defecto_fabrica'     => 'Falla mecánica atribuible a defecto de fábrica',
            'electrico_defecto_fabrica'    => 'Falla eléctrica atribuible a defecto de fábrica',
        ],
    ];

    // ✅ EXACTO lo que tu documento dice como "NO CUBRE"
    $razonesNoCubre = [
        'Uso / cuidado del cliente' => [
            'danos_transporte_uso_indebido_maltrato_negligencia_manipulacion_mantenimiento' =>
                'Daños por accidentes en el transporte, uso indebido, maltrato, negligencia, manipulación inadecuada o mantenimiento indebido.',
        ],

        'Estético / partes cosméticas' => [
            'cubiertas_plasticos_cosmeticos' =>
                'Reparación o reemplazo de cubiertas, plásticos o piezas cosméticas (molduras, acabados interiores/exteriores).',
        ],

        'Vidrios y espejos' => [
            'vidrios_espejos_post_entrega' =>
                'Vidrios y espejos que se rompan después de ser entregados.',
        ],

        'Desgaste normal' => [
            'desgaste_normal' =>
                'Uso y desgaste normal (raspaduras, decoloración y opacamiento de superficie).',
        ],

        'Causas externas / energía / no autorizado' => [
            'causas_externas_incendios_hurto_fuerza_mayor_alteraciones_software_hardware_terceros_fallas_electricas_descargas_cortos_relampagos_no_autorizado' =>
                'Daños por causas externas: incendios, hurto, fuerza mayor, alteraciones, software/hardware no suministrado, fallas eléctricas, descargas/cortos, relámpagos o reparaciones por personal no autorizado.',
        ],

        'Eléctrico / electrónico (excluido en el documento)' => [
            'danos_fallas_componentes_electricos_electronicos' =>
                'Daños o fallas de componentes eléctricos y electrónicos.',
        ],

        'Intervención de terceros / incompatibles' => [
            'intervencion_no_autorizada_terceros_incompatibles' =>
                'Daños por intervenciones por personal no autorizado y/o uso de productos incompatibles de terceros.',
        ],

        'Pérdidas y consumibles' => [
            'merma_perecederos' =>
                'Merma de productos perecederos por falla del equipo.',
            'reemplazo_refrigerante' =>
                'Reemplazo de gas refrigerante que se fugue como consecuencia de la falla.',
        ],

        'Costos logísticos' => [
            'transportes_aranceles_impuestos_importacion' =>
                'Transportes, aranceles, impuestos y gastos de importación de repuestos/refacciones.',
        ],

        'Mano de obra' => [
            'mano_obra_reemplazo_componentes' =>
                'Mano de obra para reemplazo de cualquier componente durante el tiempo de garantía.',
        ],
    ];

    /**
     * ==========================================================
     * Texto explicación (se muestra debajo del select)
     * ==========================================================
     */
    $razonHelp = [
        // NO CUBRE (resumen explicativo)
        'danos_transporte_uso_indebido_maltrato_negligencia_manipulacion_mantenimiento' =>
            'No cubre cuando la causa es manejo/transporte o un uso/mantenimiento inapropiado por parte del cliente.',

        'cubiertas_plasticos_cosmeticos' =>
            'No cubre piezas estéticas o cosméticas que no son fallo de fábrica (acabados, molduras, plásticos, cubiertas).',

        'vidrios_espejos_post_entrega' =>
            'No cubre roturas de vidrios o espejos ocurridas después de la entrega.',

        'desgaste_normal' =>
            'No cubre deterioro normal por uso y tiempo (rayones, decoloración, opacamiento).',

        'causas_externas_incendios_hurto_fuerza_mayor_alteraciones_software_hardware_terceros_fallas_electricas_descargas_cortos_relampagos_no_autorizado' =>
            'No cubre daños por eventos externos, energía/voltaje, terceros, o cuando hubo reparación por personal no autorizado.',

        'danos_fallas_componentes_electricos_electronicos' =>
            'Según el documento, no se cubren fallas/daños de componentes eléctricos y electrónicos.',

        'intervencion_no_autorizada_terceros_incompatibles' =>
            'No cubre si el equipo fue intervenido por terceros no autorizados o con piezas/productos incompatibles.',

        'merma_perecederos' =>
            'No cubre la mercancía perdida (productos perecederos). Solo aplica al equipo, no al contenido.',

        'reemplazo_refrigerante' =>
            'No cubre recarga o reemplazo de gas refrigerante por fuga, según el documento.',

        'transportes_aranceles_impuestos_importacion' =>
            'No cubre costos de transporte/importación/aranceles/impuestos de repuestos.',

        'mano_obra_reemplazo_componentes' =>
            'No cubre mano de obra durante garantía (según este documento).',

        // SÍ CUBRE (por si cierras como “cubre”)
        'defecto_materiales_mano_obra' =>
            'Cubre cuando la falla es por materiales o mano de obra defectuosa de fábrica, sin mal uso.',
        'defecto_ensamble' =>
            'Cubre cuando la falla es por ensamble incorrecto desde fábrica.',
        'mecanico_defecto_fabrica' =>
            'Cubre falla mecánica atribuible a defecto de fábrica.',
        'electrico_defecto_fabrica' =>
            'Cubre falla eléctrica atribuible a defecto de fábrica (no por voltaje externo).',
    ];

    /**
     * ==========================================================
     * Helper: mostrar label bonito en historial
     * ==========================================================
     */
    $labelRazon = function($s) use ($razonesCubre, $razonesNoCubre) {
        $k = $s->razon_codigo;
        if (!$k) return null;

        foreach ($razonesCubre as $grupo => $items) {
            if (isset($items[$k])) return $items[$k];
        }
        foreach ($razonesNoCubre as $grupo => $items) {
            if (isset($items[$k])) return $items[$k];
        }
        return $k;
    };
@endphp

{{-- ================= HEADER ================= --}}
<x-slot name="header">
    <div class="flex flex-col gap-4">
        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
            <div class="min-w-0">
                <div class="flex items-center gap-2 flex-wrap">
                    <h2 class="text-2xl font-semibold text-gray-900">Caso de garantía</h2>

                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $estadoBadge }}">
                        {{ $estadoLabels[$estado] ?? strtoupper($estado) }}
                    </span>

                    @if($textoDias)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-50 text-gray-700 border border-gray-100">
                            {{ $textoDias }}
                        </span>
                    @endif
                </div>

                <p class="text-sm text-gray-600 mt-1 truncate">
                    Serie: <span class="font-semibold text-gray-900">{{ $garantia->numero_serie }}</span>
                    <span class="text-gray-400">·</span>
                    ID: <span class="font-semibold text-gray-900">{{ $garantia->id }}</span>
                </p>
            </div>

            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.garantias.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 font-semibold">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Volver
                </a>

                <a href="{{ route('admin.garantias.edit', $garantia) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-gray-200 hover:bg-gray-100 font-semibold">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M12 20h9" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5Z"
                              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Editar
                </a>
            </div>
        </div>
    </div>
</x-slot>

{{-- ================= BODY ================= --}}
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- Alerts --}}
        @if(session('success'))
            <div class="rounded-2xl border border-green-200 bg-green-50 p-4 text-green-800 flex items-start gap-3">
                <svg class="w-5 h-5 mt-0.5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M20 6 9 17l-5-5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <div class="text-sm">{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-red-800 flex items-start gap-3">
                <svg class="w-5 h-5 mt-0.5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M12 9v4m0 4h.01M10.3 4.3h3.4L21 19H3l7.3-14.7Z" stroke="currentColor" stroke-width="2"
                          stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <div class="text-sm">{{ session('error') }}</div>
            </div>
        @endif

        {{-- Errores del form --}}
        @if ($errors->any())
            <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-red-800">
                <div class="font-semibold">Revisa el formulario</div>
                <ul class="text-sm list-disc pl-5 mt-2">
                    @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                </ul>
            </div>
        @endif

        {{-- ================= CLIENTE + DETALLE ================= --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Cliente --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Cliente</h3>
                    <p class="text-xs text-gray-500 mt-1">Titular de la garantía</p>
                </div>

                <div class="mt-4 space-y-2">
                    <div class="font-semibold text-gray-900">{{ $c?->nombre_contacto ?? '—' }}</div>
                    <div class="text-sm text-gray-600">{{ $c?->empresa ?: 'Persona natural' }}</div>

                    <div class="text-sm text-gray-700 pt-2 border-t border-gray-100 space-y-1">
                        <div><span class="text-gray-500">Documento:</span> {{ $c?->documento ?? '—' }}</div>
                        <div><span class="text-gray-500">Correo:</span> {{ $c?->email ?? '—' }}</div>
                        <div><span class="text-gray-500">Teléfono:</span> {{ $c?->telefono ?? '—' }}</div>
                    </div>
                </div>
            </div>

            {{-- Datos garantía --}}
            <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900">Detalle del caso</h3>
                <p class="text-xs text-gray-500 mt-1">Fechas y cobertura</p>

                <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="p-4 rounded-xl bg-gray-50 border border-gray-100">
                        <div class="text-xs text-gray-500">Fecha compra</div>
                        <div class="font-semibold text-gray-900">
                            {{ optional($garantia->fecha_compra)->format('Y-m-d') }}
                        </div>
                    </div>

                    <div class="p-4 rounded-xl bg-gray-50 border border-gray-100">
                        <div class="text-xs text-gray-500">Meses garantía</div>
                        <div class="font-semibold text-gray-900">{{ $garantia->meses_garantia }}</div>
                    </div>

                    <div class="p-4 rounded-xl bg-gray-50 border border-gray-100">
                        <div class="text-xs text-gray-500">Vencimiento</div>
                        <div class="font-semibold text-gray-900">
                            {{ optional($garantia->fecha_vencimiento)->format('Y-m-d') }}
                        </div>
                    </div>
                </div>

                @if($garantia->motivo)
                    <div class="mt-4">
                        <div class="text-sm font-semibold text-gray-900">Motivo reportado</div>
                        <div class="text-sm text-gray-700 mt-1">{{ $garantia->motivo }}</div>
                    </div>
                @endif

                @if($garantia->notas)
                    <div class="mt-4">
                        <div class="text-sm font-semibold text-gray-900">Notas internas</div>
                        <div class="text-sm text-gray-700 mt-1 whitespace-pre-line">{{ $garantia->notas }}</div>
                    </div>
                @endif
            </div>
        </div>

        {{-- ================= SEGUIMIENTOS ================= --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- NUEVO SEGUIMIENTO --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">Nuevo seguimiento</h3>
                    <p class="text-xs text-gray-500 mt-1">Agrega eventos al historial</p>
                </div>

                @if($esFinal)
                    <div class="p-6">
                        <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4 text-amber-900">
                            <div class="font-semibold">Caso finalizado</div>
                            <div class="text-sm mt-1">
                                Esta garantía está <span class="font-semibold">{{ $estadoLabels[$estado] ?? $estado }}</span>,
                                por lo tanto no se pueden agregar más seguimientos.
                            </div>
                        </div>
                    </div>
                @else
                    <form method="POST"
                          action="{{ route('admin.garantias.seguimientos.store', $garantia) }}"
                          enctype="multipart/form-data"
                          class="p-6 space-y-4"
                          x-data="{
                            estado: '{{ old('estado','recibida') }}',
                            decision: '{{ old('decision_cobertura','') }}',
                            razon: '{{ old('razon_codigo','') }}',
                            help: @js($razonHelp)
                          }"
                          x-effect="
                            if(estado === 'rechazada') decision = 'nocubre';
                            if(!(estado === 'cerrada' || estado === 'rechazada')) { decision = ''; razon = ''; }
                          ">
                        @csrf

                        {{-- Estado --}}
                        <div>
                            <label class="text-sm font-medium text-gray-700">Estado *</label>
                            <select name="estado" required x-model="estado"
                                    class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                                @foreach(['recibida','enrevision','enreparacion','listaparaentregar','cerrada','rechazada'] as $e)
                                    <option value="{{ $e }}">{{ $seguimientoLabels[$e] ?? $e }}</option>
                                @endforeach
                            </select>
                            @error('estado') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
                        </div>

                        {{-- Resumen rápido de qué significa "NO CUBRE" --}}
                        <div x-show="estado === 'cerrada' || estado === 'rechazada'" x-cloak
                             class="rounded-2xl border border-gray-100 bg-gray-50 p-4 text-sm">
                            <div class="font-semibold text-gray-900 flex items-center gap-2">
                                <span class="inline-flex h-2 w-2 rounded-full bg-red-600"></span>
                                Motivos de NO cobertura (según documento)
                            </div>
                            <div class="text-xs text-gray-600 mt-1">
                                Esta lista corresponde a “La Garantía limitada NO CUBRE”. Es decir: razones para negar / rechazar cobertura.
                            </div>
                        </div>

                        {{-- Decisión (solo si está cerrada o rechazada) --}}
                        <div x-show="estado === 'cerrada' || estado === 'rechazada'" x-cloak>
                            <label class="text-sm font-medium text-gray-700">Decisión de cobertura *</label>

                            <div class="mt-2 grid grid-cols-2 gap-2">
                                <button type="button"
                                        @click="decision='cubre'; razon='';"
                                        :disabled="estado==='rechazada'"
                                        :class="(estado==='rechazada')
                                            ? 'bg-gray-200 text-gray-500 cursor-not-allowed'
                                            : (decision==='cubre' ? 'bg-emerald-600 text-white' : 'bg-gray-100 text-gray-900')"
                                        class="px-4 py-2 rounded-xl font-semibold">
                                    Cubre garantía
                                </button>

                                <button type="button"
                                        @click="decision='nocubre'; razon='';"
                                        :class="decision==='nocubre' || estado==='rechazada' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-900'"
                                        class="px-4 py-2 rounded-xl font-semibold">
                                    No cubre
                                </button>
                            </div>

                            <input type="hidden" name="decision_cobertura" :value="estado==='rechazada' ? 'nocubre' : decision">

                            <p class="text-xs text-gray-500 mt-2">
                                Si el estado es <span class="font-semibold">Rechazada</span>, el sistema fuerza “No cubre”.
                            </p>

                            @error('decision_cobertura') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
                        </div>

                        {{-- Motivo + explicación --}}
                        <div x-show="estado === 'cerrada' || estado === 'rechazada'" x-cloak class="space-y-3">
                            <label class="text-sm font-medium text-gray-700">
                                Motivo (según el documento) *
                            </label>

                            <div class="rounded-2xl border border-gray-100 bg-gray-50 p-4 text-sm">
                                <div class="font-semibold text-gray-900 flex items-center gap-2">
                                    <span class="inline-flex h-2 w-2 rounded-full"
                                          :class="(estado==='rechazada' || decision==='nocubre') ? 'bg-red-600' : 'bg-emerald-600'"></span>

                                    <span x-text="(estado==='rechazada' || decision==='nocubre')
                                        ? 'NO CUBRE: selecciona una exclusión del documento'
                                        : 'SÍ CUBRE: debe ser defecto de fábrica/ensamble (mecánico/eléctrico)'">
                                    </span>
                                </div>

                                <div class="text-xs text-gray-600 mt-1">
                                    Nota: si hubo voltaje, corto, relámpago, instalación o terceros → normalmente es una exclusión (NO cubre).
                                </div>
                            </div>

                            <select name="razon_codigo"
                                    x-model="razon"
                                    x-bind:required="(estado === 'cerrada' || estado === 'rechazada')"
                                    class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                                <option value="">— Selecciona un motivo —</option>

                                {{-- NO CUBRE (las opciones del documento) --}}
                                <template x-if="estado === 'rechazada' || decision === 'nocubre'">
                                    <optgroup label="NO CUBRE GARANTÍA (documento)">
                                        @foreach($razonesNoCubre as $grupo => $items)
                                            <option value="" disabled>──────── {{ $grupo }} ────────</option>
                                            @foreach($items as $k => $v)
                                                <option value="{{ $k }}">{{ $v }}</option>
                                            @endforeach
                                        @endforeach
                                    </optgroup>
                                </template>

                                {{-- SÍ CUBRE (opcional, por si cierras como cubre) --}}
                                <template x-if="estado === 'cerrada' && decision === 'cubre'">
                                    <optgroup label="SÍ CUBRE GARANTÍA">
                                        @foreach($razonesCubre as $grupo => $items)
                                            <option value="" disabled>──────── {{ $grupo }} ────────</option>
                                            @foreach($items as $k => $v)
                                                <option value="{{ $k }}">{{ $v }}</option>
                                            @endforeach
                                        @endforeach
                                    </optgroup>
                                </template>
                            </select>

                            @error('razon_codigo') <div class="text-xs text-red-600">{{ $message }}</div> @enderror

                            {{-- Explicación automática --}}
                            <div x-show="razon && help[razon]" x-cloak
                                 class="rounded-2xl border border-blue-100 bg-blue-50 p-4 text-sm text-blue-900">
                                <div class="font-semibold flex items-center gap-2">
                                    <span class="inline-flex h-2 w-2 rounded-full bg-blue-600"></span>
                                    Resumen del motivo
                                </div>
                                <div class="mt-1" x-text="help[razon]"></div>
                            </div>

                            <div>
                                <label class="text-sm font-medium text-gray-700">Detalle adicional (opcional)</label>
                                <textarea name="razon_detalle" rows="3"
                                          class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                                          placeholder="Ej: diagnóstico, evidencia, fotos, medición de voltaje, reporte técnico, etc.">{{ old('razon_detalle') }}</textarea>
                                @error('razon_detalle') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- Nota --}}
                        <div>
                            <label class="text-sm font-medium text-gray-700">Nota</label>
                            <textarea name="nota" rows="4"
                                      class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                                      placeholder="Detalle del evento...">{{ old('nota') }}</textarea>
                            @error('nota') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
                        </div>

                        {{-- Archivo --}}
                        <div>
                            <label class="text-sm font-medium text-gray-700">Archivo</label>
                            <input type="file" name="archivo"
                                   class="mt-1 w-full rounded-xl border-gray-200 bg-white">
                            <p class="text-xs text-gray-500 mt-1">PDF/imagen o evidencia (máx 5MB).</p>
                            @error('archivo') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
                        </div>

                        <button class="w-full px-4 py-2.5 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700">
                            Agregar seguimiento
                        </button>

                        {{-- ======================================================
                             SI QUIERES QUE NUNCA EXISTA "Cubre garantía":
                             1) borra el botón "Cubre garantía"
                             2) y fuerza decision siempre a nocubre:
                                - en x-effect: decision='nocubre'
                                - y elimina el template de "SÍ CUBRE"
                           ====================================================== --}}
                    </form>
                @endif
            </div>

            {{-- HISTORIAL --}}
            <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">Historial del caso</h3>
                    <p class="text-xs text-gray-500 mt-1">Registro cronológico</p>
                </div>

                <div class="p-6">
                    @if($garantia->seguimientos->isEmpty())
                        <div class="text-center text-gray-600 py-10">
                            Aún no hay seguimientos registrados
                        </div>
                    @else
                        <ol class="relative border-s border-gray-200 ms-2">
                            @foreach($garantia->seguimientos as $s)
                                @php
                                    $label = $seguimientoLabels[$s->estado] ?? $s->estado;
                                    $b = $seguimientoBadge[$s->estado] ?? 'bg-gray-100 text-gray-700 border-gray-200';

                                    $decisionTxt = $s->decision_cobertura === 'cubre'
                                        ? 'Cubre garantía'
                                        : ($s->decision_cobertura === 'nocubre' ? 'No cubre garantía' : null);

                                    $motivoTxt = $labelRazon($s);
                                @endphp

                                <li class="mb-8 ms-6">
                                    <span class="absolute -start-2.5 mt-2 h-5 w-5 rounded-full bg-blue-600"></span>

                                    <div class="rounded-2xl border border-gray-100 p-5 shadow-sm">
                                        <div class="flex items-start justify-between gap-3">
                                            <div class="min-w-0">
                                                <div class="flex items-center gap-2 flex-wrap">
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border {{ $b }}">
                                                        {{ $label }}
                                                    </span>

                                                    <div class="text-xs text-gray-500">
                                                        {{ $s->created_at->format('Y-m-d H:i') }}
                                                        · {{ $s->created_at->diffForHumans() }}
                                                    </div>
                                                </div>

                                                @if($decisionTxt && $motivoTxt)
                                                    <div class="mt-2 text-sm">
                                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border
                                                            {{ $s->decision_cobertura==='cubre' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-red-50 text-red-700 border-red-100' }}">
                                                            {{ $decisionTxt }}
                                                        </span>

                                                        <div class="mt-2 text-xs text-gray-600">
                                                            <span class="font-semibold text-gray-900">Motivo:</span>
                                                            <span class="text-gray-800">{{ $motivoTxt }}</span>
                                                        </div>

                                                        @if($s->razon_detalle)
                                                            <div class="mt-2 text-xs text-gray-600 whitespace-pre-line">
                                                                {{ $s->razon_detalle }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="shrink-0" x-data="{ open:false }">
                                                <button type="button" @click="open = !open"
                                                        class="px-3 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 font-semibold text-sm">
                                                    Acciones
                                                </button>

                                                <div x-show="open" x-cloak @click.outside="open=false"
                                                     class="absolute right-6 mt-2 w-44 rounded-2xl bg-white border border-gray-100 shadow-lg overflow-hidden z-20">
                                                    @if($s->archivo)
                                                        <a href="{{ asset('storage/'.$s->archivo) }}" target="_blank"
                                                           class="block px-4 py-3 text-sm font-semibold text-gray-900 hover:bg-gray-50">
                                                            Ver archivo
                                                        </a>
                                                    @endif

                                                    <form method="POST" action="{{ route('admin.seguimientos.destroy', $s) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="w-full text-left px-4 py-3 text-sm font-semibold text-red-700 hover:bg-red-50">
                                                            Eliminar
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        @if($s->nota)
                                            <div class="mt-3 text-sm text-gray-700 whitespace-pre-line">
                                                {{ $s->nota }}
                                            </div>
                                        @endif

                                        @if($s->archivo)
                                            <div class="mt-4">
                                                <a href="{{ asset('storage/'.$s->archivo) }}" target="_blank"
                                                   class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-gray-900 text-white text-sm font-semibold hover:bg-gray-800">
                                                    Ver archivo
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ol>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
</x-app-layout>
