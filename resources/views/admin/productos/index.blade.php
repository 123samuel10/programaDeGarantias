{{-- resources/views/admin/productos/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4">
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <h2 class="text-xl sm:text-2xl font-semibold text-gray-900">Productos</h2>
                    <p class="text-sm text-gray-600">Catálogo para asociar productos a garantías.</p>
                </div>

                <a href="{{ route('admin.productos.create') }}"
                   class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 shadow-sm whitespace-nowrap">
                    <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-white/15">
                        <svg class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </span>
                    Nuevo producto
                </a>
            </div>

            {{-- Buscar --}}
            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-4">
                <form method="GET" action="{{ route('admin.productos.index') }}"
                      class="flex flex-col lg:flex-row gap-3 lg:items-center">
                    <div class="flex-1">
                        <div class="relative">
                            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M21 21l-4.3-4.3m1.8-5.2a7 7 0 11-14 0 7 7 0 0114 0z"
                                          stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                            </span>
                            <input name="buscar" value="{{ request('buscar') }}"
                                   placeholder="Buscar por marca, modelo, nombre, tipo o descripción…"
                                   class="w-full pl-10 pr-3 py-2.5 rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 text-sm"/>
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <button class="px-4 py-2.5 rounded-xl bg-gray-900 text-white text-sm font-semibold hover:bg-gray-800">
                            Buscar
                        </button>
                        @if(request('buscar'))
                            <a href="{{ route('admin.productos.index') }}"
                               class="px-4 py-2.5 rounded-xl bg-gray-100 text-gray-900 text-sm font-semibold hover:bg-gray-200">
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
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
                @forelse($productos as $p)
                    @php
                        // ✅ URL REAL para la foto (soporta ruta storage o URL http)
                        $fotoUrl = null;
                        if (!empty($p->foto)) {
                            if (str_starts_with($p->foto, 'http://') || str_starts_with($p->foto, 'https://')) {
                                $fotoUrl = $p->foto;
                            } elseif (str_starts_with($p->foto, 'storage/')) {
                                $fotoUrl = asset($p->foto);
                            } elseif (str_starts_with($p->foto, 'public/')) {
                                $fotoUrl = asset('storage/' . substr($p->foto, 7));
                            } else {
                                // asume: productos/xxx.jpg
                                $fotoUrl = asset('storage/' . $p->foto);
                            }
                        }
                    @endphp

                    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition"
                         x-data="{
                            open:false,
                            producto: @js([
                                'id' => $p->id,
                                'modelo' => $p->modelo,
                                'nombre_producto' => $p->nombre_producto,
                                'marca' => $p->marca,
                                'tipo_equipo' => $p->tipo_equipo,
                                'descripcion' => $p->descripcion,
                                'foto' => $fotoUrl,
                                'repisas_iluminadas' => $p->repisas_iluminadas,
                                'refrigerante' => $p->refrigerante,
                                'longitud' => $p->longitud,
                                'profundidad' => $p->profundidad,
                                'altura' => $p->altura,
                               'created_at' => $p->created_at ? $p->created_at->timezone('America/Bogota')->format('Y-m-d H:i') : null,
'updated_at' => $p->updated_at ? $p->updated_at->timezone('America/Bogota')->format('Y-m-d H:i') : null,

                            ]),
                            dims() {
                                const L = this.producto.longitud ?? null;
                                const P = this.producto.profundidad ?? null;
                                const A = this.producto.altura ?? null;
                                if (!L && !P && !A) return '—';
                                return `${L ?? '—'} · ${P ?? '—'} · ${A ?? '—'} cm`;
                            }
                         }">

                        {{-- FOTO HERO --}}
                        <div class="relative">
                            <div class="h-48 bg-gray-50">
                                @if($fotoUrl)
                                    <img src="{{ $fotoUrl }}"
                                         alt="Foto"
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <div class="w-16 h-16 rounded-2xl bg-white border border-gray-200 flex items-center justify-center shadow-sm">
                                            <svg class="w-8 h-8 text-gray-400" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                <path d="M4 7.5A2.5 2.5 0 0 1 6.5 5h11A2.5 2.5 0 0 1 20 7.5v9A2.5 2.5 0 0 1 17.5 19h-11A2.5 2.5 0 0 1 4 16.5v-9Z"
                                                      stroke="currentColor" stroke-width="1.8"/>
                                                <path d="M8 10.5h8M8 13.5h6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                            </svg>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            {{-- Badge foto --}}
                            {{-- <div class="absolute top-3 left-3">
                                @if($fotoUrl)
                                    <span class="inline-flex items-center rounded-full bg-green-50 text-green-700 border border-green-100 px-2.5 py-1 text-[11px] font-bold">
                                        FOTO
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-gray-100 text-gray-700 border border-gray-200 px-2.5 py-1 text-[11px] font-bold">
                                        SIN FOTO
                                    </span>
                                @endif
                            </div> --}}

                            {{-- Acciones rápidas --}}
                            <div class="absolute top-3 right-3 flex gap-2">
                                <button type="button" @click="open=true"
                                        class="px-3 py-2 rounded-xl bg-gray-900 text-white text-xs font-semibold hover:bg-gray-800 shadow-sm">
                                    Ver
                                </button>

                                <a href="{{ route('admin.productos.edit', $p) }}"
                                   class="px-3 py-2 rounded-xl bg-white/95 border border-gray-200 text-gray-900 text-xs font-semibold hover:bg-white shadow-sm">
                                    Editar
                                </a>
                            </div>
                        </div>

                        {{-- BODY --}}
                        <div class="p-5 space-y-3">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <div class="text-lg font-semibold text-gray-900 truncate">
                                        {{ $p->nombre_producto ?? '—' }}
                                    </div>
                                    {{-- <div class="text-xs text-gray-500 mt-0.5">
                                        Identificación #{{ $p->id }}
                                    </div> --}}
                                </div>

                                <div class="shrink-0">
                                    <span class="inline-flex items-center rounded-full bg-gray-100 text-gray-800 px-2.5 py-1 text-xs font-semibold">
                                        {{ $p->modelo ?? 'Sin modelo' }}
                                    </span>
                                </div>
                            </div>

                            <div class="text-sm text-gray-600 line-clamp-2">
                                {{ $p->descripcion ?: 'Sin descripción.' }}
                            </div>

                            {{-- Chips --}}
                            <div class="flex flex-wrap gap-2 pt-1">
                                @if($p->marca)
                                    <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-1 text-[11px] font-semibold text-gray-800">
                                        {{ $p->marca }}
                                    </span>
                                @endif

                                @if($p->tipo_equipo)
                                    <span class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-1 text-[11px] font-semibold text-blue-700">
                                        {{ $p->tipo_equipo }}
                                    </span>
                                @endif

                                @if($p->refrigerante)
                                    <span class="inline-flex items-center rounded-full bg-indigo-50 px-2.5 py-1 text-[11px] font-semibold text-indigo-700">
                                        Referencia: {{ $p->refrigerante }}
                                    </span>
                                @endif

                                @if(!is_null($p->repisas_iluminadas))
                                    <span class="inline-flex items-center rounded-full bg-amber-50 px-2.5 py-1 text-[11px] font-semibold text-amber-800">
                                        Repisas: {{ $p->repisas_iluminadas }}
                                    </span>
                                @endif

                                <span class="inline-flex items-center rounded-full bg-gray-50 border border-gray-100 px-2.5 py-1 text-[11px] font-semibold text-gray-700">
                                    Medidas: {{ ($p->longitud || $p->profundidad || $p->altura) ? (($p->longitud ?? '—').'·'.($p->profundidad ?? '—').'·'.($p->altura ?? '—').' cm') : '—' }}
                                </span>
                            </div>
                        </div>

                        {{-- FOOTER --}}
                        <div class="px-5 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                            <div class="text-xs text-gray-500">
                              Actualizado: {{ $p->updated_at ? $p->updated_at->timezone('America/Bogota')->format('Y-m-d H:i') : '—' }}

                            </div>

                            {{-- Eliminar --}}
                            <div x-data="{ del:false }">
                                <button type="button" @click="del=true"
                                        class="px-3 py-2 rounded-xl bg-red-50 text-red-700 hover:bg-red-100 text-xs font-semibold">
                                    Eliminar
                                </button>

                                <div x-show="del" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
                                    <div class="absolute inset-0 bg-gray-900/50" @click="del=false"></div>
                                    <div class="relative w-full max-w-md bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                                        <div class="p-6">
                                            <div class="font-semibold text-gray-900">Eliminar producto</div>
                                            <p class="text-sm text-gray-600 mt-1">
                                                <span class="font-semibold">{{ $p->modelo ?? '—' }}</span>
                                                · {{ $p->nombre_producto ?? 'Sin nombre' }}
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
                                            <form method="POST" action="{{ route('admin.productos.destroy', $p) }}">
                                                @csrf @method('DELETE')
                                                <button class="px-4 py-2 rounded-xl bg-red-600 text-white font-semibold hover:bg-red-700">
                                                    Sí, eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- SLIDEOVER (sin doble scroll + más pro) --}}
                        <div x-show="open" x-cloak class="fixed inset-0 z-50">
                            <div class="absolute inset-0 bg-gray-900/50" @click="open=false"></div>

                            <div class="absolute right-0 top-0 h-full w-full max-w-[680px] bg-white shadow-2xl border-l border-gray-100 overflow-hidden">
                                {{-- Header sticky --}}
                                <div class="sticky top-0 z-20 bg-white/95 backdrop-blur border-b border-gray-100">
                                    <div class="p-6 flex items-start justify-between gap-4">
                                        <div class="min-w-0">
                                            <div class="text-[11px] tracking-wide text-gray-500 font-bold uppercase">
                                                Ficha del producto
                                            </div>

                                            <div class="mt-1 flex items-center gap-3">
                                                {{-- mini avatar --}}
                                                <div class="w-10 h-10 rounded-2xl border border-gray-200 bg-gray-50 overflow-hidden shrink-0 flex items-center justify-center">
                                                    <template x-if="producto.foto">
                                                        <img :src="producto.foto" class="w-full h-full object-cover" alt="Mini foto">
                                                    </template>
                                                    <template x-if="!producto.foto">
                                                        <svg class="w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                            <path d="M4 7.5A2.5 2.5 0 0 1 6.5 5h11A2.5 2.5 0 0 1 20 7.5v9A2.5 2.5 0 0 1 17.5 19h-11A2.5 2.5 0 0 1 4 16.5v-9Z"
                                                                  stroke="currentColor" stroke-width="1.8"/>
                                                        </svg>
                                                    </template>
                                                </div>

                                                <div class="min-w-0">
                                                    <h3 class="text-2xl font-semibold text-gray-900 truncate">
                                                        <span x-text="producto.nombre_producto || 'Sin nombre'"></span>
                                                    </h3>

                                                    <div class="mt-2 flex flex-wrap items-center gap-2 text-sm text-gray-600">
                                                        <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-800">
                                                            <span x-text="producto.modelo || '—'"></span>
                                                        </span>
                                                        <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-800">
                                                            <span x-text="producto.marca || '—'"></span>
                                                        </span>
                                                        <template x-if="producto.tipo_equipo">
                                                            <span class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700">
                                                                <span x-text="producto.tipo_equipo"></span>
                                                            </span>
                                                        </template>
                                                        <span class="text-xs text-gray-400 font-semibold">
                                                            Identificación #<span x-text="producto.id"></span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <button @click="open=false"
                                                class="shrink-0 px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 font-semibold">
                                            Cerrar
                                        </button>
                                    </div>
                                </div>

                                {{-- Body (SOLO este scroll) --}}
                                <div class="overflow-y-auto h-[calc(100%-148px)]">

                                    {{-- FOTO HERO --}}
                                    <div class="p-6">
                                        <div class="rounded-3xl border border-gray-100 overflow-hidden bg-gray-50">
                                            <div class="px-5 py-4 bg-white border-b border-gray-100 flex items-center justify-between">
                                                <div>
                                                    <div class="font-semibold text-gray-900">Foto</div>
                                                    <div class="text-xs text-gray-500">Vista previa grande del producto.</div>
                                                </div>

                                                <div class="flex items-center gap-2">
                                                    <template x-if="producto.foto">
                                                        <a :href="producto.foto" target="_blank"
                                                           class="px-3 py-2 rounded-xl bg-white border border-gray-200 hover:bg-gray-100 text-xs font-semibold">
                                                            Abrir imagen
                                                        </a>
                                                    </template>

                                                    <template x-if="producto.foto">
                                                        <span class="inline-flex items-center rounded-full bg-green-50 text-green-700 px-2.5 py-1 text-xs font-semibold border border-green-100">
                                                            Disponible
                                                        </span>
                                                    </template>

                                                    <template x-if="!producto.foto">
                                                        <span class="inline-flex items-center rounded-full bg-gray-100 text-gray-700 px-2.5 py-1 text-xs font-semibold border border-gray-200">
                                                            Sin foto
                                                        </span>
                                                    </template>
                                                </div>
                                            </div>

                                            <div class="p-5">
                                                <template x-if="producto.foto">
                                                    <div class="rounded-3xl bg-white border border-gray-100 overflow-hidden">
                                                        <img :src="producto.foto" alt="Foto del producto"
                                                             class="w-full h-[320px] sm:h-[360px] object-cover">
                                                    </div>
                                                </template>

                                                <template x-if="!producto.foto">
                                                    <div class="rounded-3xl border border-dashed border-gray-200 bg-white p-10 text-center">
                                                        <div class="text-base font-semibold text-gray-900">Sin foto registrada</div>
                                                        <div class="text-sm text-gray-500 mt-1">Agrega una desde “Editar”.</div>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- KPIs --}}
                                    <div class="px-6 pb-6">
                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                            <div class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
                                                <div class="text-xs text-gray-500 font-semibold">Referencia</div>
                                                <div class="mt-1 text-base font-semibold text-gray-900" x-text="producto.refrigerante || '—'"></div>
                                            </div>

                                            <div class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
                                                <div class="text-xs text-gray-500 font-semibold">Repisas</div>
                                                <div class="mt-1 text-base font-semibold text-gray-900" x-text="(producto.repisas_iluminadas ?? '—')"></div>
                                            </div>

                                            <div class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
                                                <div class="text-xs text-gray-500 font-semibold">Dimensiones</div>
                                                <div class="mt-1 text-sm font-semibold text-gray-900" x-text="dims()"></div>
                                                <div class="text-[11px] text-gray-500 mt-1">L · P · A (cm)</div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Descripción --}}
                                    <div class="px-6 pb-6">
                                        <div class="rounded-2xl border border-gray-100 overflow-hidden bg-white">
                                            <div class="px-5 py-4 border-b border-gray-100">
                                                <div class="font-semibold text-gray-900">Descripción</div>
                                                <div class="text-xs text-gray-500">Información útil para diagnóstico y soporte.</div>
                                            </div>
                                            <div class="p-5">
                                                <p class="text-sm text-gray-700 whitespace-pre-line"
                                                   x-text="producto.descripcion || 'Sin descripción.'"></p>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Registro --}}
                                    <div class="px-6 pb-24">
                                        <div class="rounded-2xl border border-gray-100 overflow-hidden bg-white">
                                            <div class="px-5 py-4 border-b border-gray-100">
                                                <div class="font-semibold text-gray-900">Registro</div>
                                            </div>
                                            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                                                <div class="rounded-2xl bg-gray-50 border border-gray-100 p-4">
                                                    <div class="text-xs text-gray-500 font-semibold">Creado</div>
                                                    <div class="mt-1 font-semibold text-gray-900" x-text="producto.created_at || '—'"></div>
                                                </div>
                                                <div class="rounded-2xl bg-gray-50 border border-gray-100 p-4">
                                                    <div class="text-xs text-gray-500 font-semibold">Actualizado</div>
                                                    <div class="mt-1 font-semibold text-gray-900" x-text="producto.updated_at || '—'"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                {{-- Footer sticky --}}
                                <div class="sticky bottom-0 z-20 border-t border-gray-100 bg-white/95 backdrop-blur">
                                    <div class="p-5 flex justify-end gap-2">
                                        <a :href="'{{ url('/admin/productos') }}/' + producto.id + '/edit'"
                                           class="px-4 py-2 rounded-xl bg-white border border-gray-200 hover:bg-gray-100 font-semibold">
                                            Editar
                                        </a>
                                        <button @click="open=false"
                                                class="px-4 py-2 rounded-xl bg-gray-900 text-white font-semibold hover:bg-gray-800">
                                            Listo
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- /slideover --}}
                    </div>
                @empty
                    <div class="sm:col-span-2 xl:col-span-3">
                        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-10 text-center">
                            <div class="text-lg font-semibold text-gray-900">No hay productos registrados</div>
                            <div class="text-sm text-gray-500 mt-1">Crea un producto para empezar el catálogo.</div>
                            <a href="{{ route('admin.productos.create') }}"
                               class="mt-5 inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700 shadow-sm">
                                Nuevo producto
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- Paginación --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-4 py-3">
                {{ $productos->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
