{{-- resources/views/admin/garantias/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4">
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <h2 class="text-xl sm:text-2xl font-semibold text-gray-900">Garantías</h2>
                    <p class="text-sm text-gray-600">Registra, controla estados y da seguimiento a casos.</p>
                </div>

                <a href="{{ route('admin.garantias.create') }}"
                   class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 shadow-sm whitespace-nowrap">
                    <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-white/15">
                        <svg class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </span>
                    Nueva garantía
                </a>
            </div>

            {{-- Buscar / filtrar --}}
            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-4">
                <form method="GET" action="{{ route('admin.garantias.index') }}"
                      class="flex flex-col lg:flex-row gap-3 lg:items-center lg:justify-between">
                    <div class="flex-1">
                        <div class="relative">
                            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M21 21l-4.3-4.3m1.8-5.2a7 7 0 11-14 0 7 7 0 0114 0z"
                                          stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                            </span>
                            <input name="buscar" value="{{ request('buscar') }}"
                                   placeholder="Buscar por serie, cliente o producto…"
                                   class="w-full pl-10 pr-3 py-2.5 rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 text-sm"/>
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <select name="estado"
                                class="px-3 py-2.5 rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 text-sm">
                            <option value="">Todos los estados</option>
                            @foreach(['activa','enproceso','vencida','cerrada','rechazada'] as $e)
                                <option value="{{ $e }}" @selected(request('estado')===$e)>
                                    {{ strtoupper($e) }}
                                </option>
                            @endforeach
                        </select>

                        <button class="px-4 py-2.5 rounded-xl bg-gray-900 text-white text-sm font-semibold hover:bg-gray-800">
                            Filtrar
                        </button>

                        @if(request('buscar') || request('estado'))
                            <a href="{{ route('admin.garantias.index') }}"
                               class="px-4 py-2.5 rounded-xl bg-gray-100 text-gray-800 text-sm font-semibold hover:bg-gray-200">
                                Limpiar
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="rounded-2xl border border-green-200 bg-green-50 p-4 text-green-800 flex items-start gap-3">
                    <svg class="w-5 h-5 mt-0.5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M20 6 9 17l-5-5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <div class="text-sm">{{ session('success') }}</div>
                </div>
            @endif

            {{-- GRID PRO --}}
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
                @forelse($garantias as $g)
                    @php
                        $cliente = $g->cliente;
                        $p = $g->producto;

                        $badge = [
                            'activa' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                            'enproceso' => 'bg-blue-50 text-blue-700 border-blue-100',
                            'vencida' => 'bg-amber-50 text-amber-800 border-amber-100',
                            'cerrada' => 'bg-gray-100 text-gray-700 border-gray-200',
                            'rechazada' => 'bg-red-50 text-red-700 border-red-100',
                        ][$g->estado] ?? 'bg-gray-100 text-gray-700 border-gray-200';

                        // ✅ Resolver foto (URL o storage)
                        $fotoUrl = null;
                        if ($p && !empty($p->foto)) {
                            if (str_starts_with($p->foto, 'http://') || str_starts_with($p->foto, 'https://')) {
                                $fotoUrl = $p->foto;
                            } elseif (str_starts_with($p->foto, 'storage/')) {
                                $fotoUrl = asset($p->foto);
                            } elseif (str_starts_with($p->foto, 'public/')) {
                                $fotoUrl = asset('storage/' . substr($p->foto, 7));
                            } else {
                                $fotoUrl = asset('storage/' . $p->foto);
                            }
                        }

                        $dims = '—';
                        if ($p) {
                            $L = $p->longitud;
                            $P = $p->profundidad;
                            $A = $p->altura;
                            if ($L || $P || $A) $dims = ($L ?? '—').'·'.($P ?? '—').'·'.($A ?? '—').' cm';
                        }

                        $actualizadoCO = $g->updated_at
                            ? $g->updated_at->timezone('America/Bogota')->format('Y-m-d H:i')
                            : '—';

                        $entrega = $g->fecha_entrega_fabrica ? $g->fecha_entrega_fabrica->format('Y-m-d') : '—';
                        $vence = $g->fecha_vencimiento ? $g->fecha_vencimiento->format('Y-m-d') : '—';
                    @endphp

                    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition"
                         x-data="{ del:false }">

                        {{-- HEADER --}}
                        <div class="p-5 pb-4">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <div class="text-xs text-gray-500 font-semibold">Serie</div>
                                    <div class="text-lg font-semibold text-gray-900 truncate">
                                        {{ $g->numero_serie }}
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        Caso #{{ $g->id }} · <span class="text-gray-400">Actualizado:</span> {{ $actualizadoCO }}
                                    </div>
                                </div>

                                <div class="shrink-0">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border {{ $badge }}">
                                        {{ strtoupper($g->estado) }}
                                    </span>
                                </div>
                            </div>

                            {{-- Cliente --}}
                            <div class="mt-4 flex items-center gap-3 rounded-2xl border border-gray-100 bg-gray-50 p-3">
                                <div class="w-10 h-10 rounded-2xl bg-white border border-gray-200 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <path d="M20 21v-1a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v1" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                        <path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" stroke="currentColor" stroke-width="2"/>
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <div class="text-xs text-gray-500 font-semibold">Cliente</div>
                                    <div class="font-semibold text-gray-900 truncate">
                                        {{ $cliente?->nombre_contacto ?? '—' }}
                                    </div>
                                    <div class="text-xs text-gray-500 truncate">
                                        {{ $cliente?->empresa ? $cliente->empresa : 'Persona natural' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- PRODUCTO --}}
                        <div class="px-5 pb-5">
                            @if($p)
                                <div class="rounded-3xl border border-gray-100 overflow-hidden">
                                    <div class="flex gap-4 p-4">
                                        <div class="w-16 h-16 rounded-2xl border border-gray-200 bg-gray-50 overflow-hidden shrink-0 flex items-center justify-center">
                                            @if($fotoUrl)
                                                <img src="{{ $fotoUrl }}" alt="Foto producto" class="w-full h-full object-cover">
                                            @else
                                                <svg class="w-7 h-7 text-gray-400" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                    <path d="M4 7.5A2.5 2.5 0 0 1 6.5 5h11A2.5 2.5 0 0 1 20 7.5v9A2.5 2.5 0 0 1 17.5 19h-11A2.5 2.5 0 0 1 4 16.5v-9Z"
                                                          stroke="currentColor" stroke-width="1.8"/>
                                                    <path d="M8 10.5h8M8 13.5h6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                                </svg>
                                            @endif
                                        </div>

                                        <div class="min-w-0 flex-1">
                                            <div class="text-xs text-gray-500 font-semibold">Producto</div>
                                            <div class="font-semibold text-gray-900 truncate">
                                                {{ $p->nombre_producto ?: 'Producto sin nombre' }}
                                            </div>
                                            <div class="text-xs text-gray-500 mt-1">
                                                <span class="font-semibold text-gray-700">{{ $p->marca ?: '—' }}</span>
                                                <span class="text-gray-400">·</span>
                                                Modelo: <span class="font-semibold text-gray-700">{{ $p->modelo ?: '—' }}</span>
                                            </div>

                                            <div class="mt-3 flex flex-wrap gap-2">
                                                <span class="inline-flex items-center rounded-full bg-blue-50 text-blue-700 border border-blue-100 px-2.5 py-1 text-[11px] font-semibold">
                                                    ID producto #{{ $p->id }}
                                                </span>

                                                @if($p->tipo_equipo)
                                                    <span class="inline-flex items-center rounded-full bg-blue-50 text-blue-700 border border-blue-100 px-2.5 py-1 text-[11px] font-semibold">
                                                        {{ $p->tipo_equipo }}
                                                    </span>
                                                @endif

                                                @if($p->refrigerante)
                                                    <span class="inline-flex items-center rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100 px-2.5 py-1 text-[11px] font-semibold">
                                                        Ref: {{ $p->refrigerante }}
                                                    </span>
                                                @endif

                                                @if(!is_null($p->repisas_iluminadas))
                                                    <span class="inline-flex items-center rounded-full bg-amber-50 text-amber-800 border border-amber-100 px-2.5 py-1 text-[11px] font-semibold">
                                                        Repisas: {{ $p->repisas_iluminadas }}
                                                    </span>
                                                @endif

                                                <span class="inline-flex items-center rounded-full bg-gray-50 text-gray-700 border border-gray-100 px-2.5 py-1 text-[11px] font-semibold">
                                                    Medidas: {{ $dims }}
                                                </span>
                                            </div>

                                            @if($p->descripcion)
                                                <div class="mt-3 text-sm text-gray-600 line-clamp-2">
                                                    {{ $p->descripcion }}
                                                </div>
                                            @else
                                                <div class="mt-3 text-sm text-gray-500">
                                                    Sin descripción.
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="rounded-3xl border border-gray-100 bg-gray-50 p-5">
                                    <div class="font-semibold text-gray-900">Sin producto asociado</div>
                                    <div class="text-sm text-gray-600 mt-1">
                                        Asócialo desde <span class="font-semibold">Editar</span> para que el caso quede completo.
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- FECHAS --}}
                        <div class="px-5 pb-5">
                            <div class="grid grid-cols-2 gap-3">
                                <div class="rounded-2xl border border-gray-100 bg-white p-4">
                                    <div class="text-xs text-gray-500 font-semibold">Entrega fábrica</div>
                                    <div class="mt-1 font-semibold text-gray-900">{{ $entrega }}</div>
                                </div>

                                <div class="rounded-2xl border border-gray-100 bg-white p-4">
                                    <div class="text-xs text-gray-500 font-semibold">Vencimiento</div>
                                    <div class="mt-1 font-semibold text-gray-900">{{ $vence }}</div>
                                </div>
                            </div>
                        </div>

                        {{-- FOOTER ACCIONES --}}
                        <div class="px-5 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between gap-2">
                            <a href="{{ route('admin.garantias.show', $g) }}"
                               class="px-4 py-2 rounded-xl bg-gray-900 text-white text-sm font-semibold hover:bg-gray-800">
                                Ver caso
                            </a>

                            <div class="flex gap-2">
                                <a href="{{ route('admin.garantias.edit', $g) }}"
                                   class="px-4 py-2 rounded-xl bg-white border border-gray-200 hover:bg-gray-100 text-sm font-semibold">
                                    Editar
                                </a>

                                <button type="button" @click="del=true"
                                        class="px-4 py-2 rounded-xl bg-red-50 text-red-700 hover:bg-red-100 text-sm font-semibold">
                                    Eliminar
                                </button>
                            </div>
                        </div>

                        {{-- MODAL ELIMINAR --}}
                        <div x-show="del" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
                            <div class="absolute inset-0 bg-gray-900/50" @click="del=false"></div>

                            <div class="relative w-full max-w-md bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                                <div class="p-6">
                                    <div class="font-semibold text-gray-900 text-lg">Eliminar garantía</div>
                                    <p class="text-sm text-gray-600 mt-1">
                                        Vas a eliminar el caso con serie:
                                        <span class="font-semibold text-gray-900">{{ $g->numero_serie }}</span>
                                    </p>
                                    <div class="mt-4 rounded-xl bg-red-50 border border-red-100 p-3 text-sm text-red-800">
                                        Esta acción no se puede deshacer.
                                    </div>
                                </div>
                                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-2">
                                    <button @click="del=false"
                                            class="px-4 py-2 rounded-xl bg-white border border-gray-200 hover:bg-gray-100 font-semibold">
                                        Cancelar
                                    </button>
                                    <form method="POST" action="{{ route('admin.garantias.destroy', $g) }}">
                                        @csrf @method('DELETE')
                                        <button class="px-4 py-2 rounded-xl bg-red-600 text-white font-semibold hover:bg-red-700">
                                            Sí, eliminar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                @empty
                    <div class="md:col-span-2 xl:col-span-3">
                        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-10 text-center">
                            <div class="text-lg font-semibold text-gray-900">No hay garantías registradas</div>
                            <div class="text-sm text-gray-500 mt-1">Crea la primera garantía para iniciar el control.</div>
                            <a href="{{ route('admin.garantias.create') }}"
                               class="mt-5 inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700 shadow-sm">
                                Nueva garantía
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- Paginación --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-4 py-3">
                {{ $garantias->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
