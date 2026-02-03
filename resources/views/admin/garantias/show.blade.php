<x-app-layout>
@php
    /**
     * ==========================
     * Datos base
     * ==========================
     */
    $c = $garantia->cliente;

    $estado = $garantia->estado ?? 'activa';

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
    ][$estado] ?? 'bg-gray-100 text-gray-700 border-gray-200';

    // ✅ Labels PRO para seguimientos (para que NO se vea "Enrevision")
    $seguimientoLabels = [
        'recibida' => 'Recibida',
        'enrevision' => 'En revisión',
        'enreparacion' => 'En reparación',
        'listaparaentregar' => 'Lista para entregar',
        'cerrada' => 'Cerrada',
        'rechazada' => 'Rechazada',
    ];

    $vence = $garantia->fecha_vencimiento;
    $dias = $vence ? now()->startOfDay()->diffInDays($vence->startOfDay(), false) : null;

    if ($dias !== null) {
        if ($dias > 0) $textoDias = "Vence en {$dias} días";
        elseif ($dias === 0) $textoDias = "Vence hoy";
        else $textoDias = "Venció hace ".abs($dias)." días";
    } else {
        $textoDias = null;
    }

    // Badge pro para estados de seguimiento
    $seguimientoBadge = [
        'recibida' => 'bg-blue-50 text-blue-700 border-blue-100',
        'enrevision' => 'bg-amber-50 text-amber-800 border-amber-100',
        'enreparacion' => 'bg-indigo-50 text-indigo-700 border-indigo-100',
        'listaparaentregar' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
        'cerrada' => 'bg-gray-100 text-gray-700 border-gray-200',
        'rechazada' => 'bg-red-50 text-red-700 border-red-100',
    ];
@endphp

{{-- ================= HEADER ================= --}}
<x-slot name="header">
    <div class="flex flex-col gap-4">
        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
            <div class="min-w-0">
                <div class="flex items-center gap-2 flex-wrap">
                    <h2 class="text-2xl font-semibold text-gray-900">
                        Caso de garantía
                    </h2>

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
                    Serie:
                    <span class="font-semibold text-gray-900">{{ $garantia->numero_serie }}</span>
                    <span class="text-gray-400">·</span>
                    ID:
                    <span class="font-semibold text-gray-900">{{ $garantia->id }}</span>
                </p>
            </div>

            {{-- ✅ Acciones visibles (sin eliminar caso) --}}
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

        {{-- ================= CLIENTE + DATOS ================= --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Cliente --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Cliente</h3>
                        <p class="text-xs text-gray-500 mt-1">Titular de la garantía</p>
                    </div>
                </div>

                <div class="mt-4 space-y-2">
                    <div class="font-semibold text-gray-900">
                        {{ $c?->nombre_contacto ?? '—' }}
                    </div>

                    <div class="text-sm text-gray-600">
                        {{ $c?->empresa ?: 'Persona natural' }}
                    </div>

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
                        <div class="font-semibold text-gray-900">
                            {{ $garantia->meses_garantia }}
                        </div>
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
                        <div class="text-sm font-semibold text-gray-900">Motivo</div>
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

                <form method="POST"
                      action="{{ route('admin.garantias.seguimientos.store', $garantia) }}"
                      enctype="multipart/form-data"
                      class="p-6 space-y-4">
                    @csrf

                    <div>
                        <label class="text-sm font-medium text-gray-700">Estado *</label>
                        <select name="estado" required
                                class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                            @foreach(['recibida','enrevision','enreparacion','listaparaentregar','cerrada','rechazada'] as $e)
                                <option value="{{ $e }}">{{ $seguimientoLabels[$e] ?? $e }}</option>
                            @endforeach
                        </select>
                        @error('estado')
                            <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-700">Nota</label>
                        <textarea name="nota" rows="4"
                                  class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                                  placeholder="Detalle del evento...">{{ old('nota') }}</textarea>
                        @error('nota')
                            <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-700">Archivo</label>
                        <input type="file" name="archivo"
                               class="mt-1 w-full rounded-xl border-gray-200 bg-white">
                        <p class="text-xs text-gray-500 mt-1">PDF/imagen o evidencia (máx 5MB).</p>
                        @error('archivo')
                            <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <button class="w-full px-4 py-2.5 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700">
                        Agregar seguimiento
                    </button>
                </form>
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
                                            </div>

                                            {{-- ⚠️ Ajusta la ruta si tu nombre real es diferente --}}
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
