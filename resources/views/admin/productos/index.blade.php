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
                    + Nuevo producto
                </a>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-4">
                <form method="GET" action="{{ route('admin.productos.index') }}" class="flex gap-2">
                    <input name="buscar" value="{{ request('buscar') }}"
                           placeholder="Buscar por marca, modelo, nombre, tipo o descripción…"
                           class="flex-1 rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                    <button class="px-4 py-2 rounded-xl bg-gray-900 text-white font-semibold hover:bg-gray-800">
                        Buscar
                    </button>
                    @if(request('buscar'))
                        <a href="{{ route('admin.productos.index') }}"
                           class="px-4 py-2 rounded-xl bg-gray-100 font-semibold hover:bg-gray-200">
                            Limpiar
                        </a>
                    @endif
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="rounded-2xl border border-green-200 bg-green-50 p-4 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-gray-600">
                            <tr>
                                <th class="px-6 py-3 text-left font-semibold">Modelo</th>
                                <th class="px-6 py-3 text-left font-semibold">Nombre</th>
                                <th class="px-6 py-3 text-left font-semibold">Marca</th>
                                <th class="px-6 py-3 text-left font-semibold">Tipo</th>
                                <th class="px-6 py-3 text-left font-semibold">Indicadores</th>
                                <th class="px-6 py-3 text-right font-semibold">Acciones</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @forelse($productos as $p)
                                <tr class="hover:bg-gray-50/60"
                                    x-data="{
                                        open:false,
                                        producto: @js([
                                            'id' => $p->id,
                                            'modelo' => $p->modelo,
                                            'nombre_producto' => $p->nombre_producto,
                                            'marca' => $p->marca,
                                            'tipo_equipo' => $p->tipo_equipo,
                                            'descripcion' => $p->descripcion,
                                            'foto' => $p->foto,
                                            'repisas_iluminadas' => $p->repisas_iluminadas,
                                            'refrigerante' => $p->refrigerante,
                                            'longitud' => $p->longitud,
                                            'profundidad' => $p->profundidad,
                                            'altura' => $p->altura,
                                            'created_at' => optional($p->created_at)->format('Y-m-d H:i'),
                                            'updated_at' => optional($p->updated_at)->format('Y-m-d H:i'),
                                        ]),
                                        dims() {
                                            const L = this.producto.longitud ?? null;
                                            const P = this.producto.profundidad ?? null;
                                            const A = this.producto.altura ?? null;
                                            if (!L && !P && !A) return '—';
                                            return `${L ?? '—'} L · ${P ?? '—'} P · ${A ?? '—'} A`;
                                        }
                                    }">

                                    <td class="px-6 py-4 font-semibold text-gray-900">
                                        {{ $p->modelo ?? '—' }}
                                        <div class="text-xs text-gray-500 font-normal mt-1">ID #{{ $p->id }}</div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-gray-900">
                                            {{ $p->nombre_producto ?? '—' }}
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1 line-clamp-1">
                                            {{ $p->descripcion ?? 'Sin descripción' }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">{{ $p->marca ?? '—' }}</td>
                                    <td class="px-6 py-4">{{ $p->tipo_equipo ?? '—' }}</td>

                                    <td class="px-6 py-4">
                                        <div class="flex flex-wrap gap-2">
                                            @if($p->refrigerante)
                                                <span class="inline-flex items-center rounded-full bg-blue-50 text-blue-700 px-2.5 py-1 text-xs font-semibold">
                                                    {{ $p->refrigerante }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center rounded-full bg-gray-100 text-gray-700 px-2.5 py-1 text-xs font-semibold">
                                                    Sin refrigerante
                                                </span>
                                            @endif

                                            @if(!is_null($p->repisas_iluminadas))
                                                <span class="inline-flex items-center rounded-full bg-amber-50 text-amber-800 px-2.5 py-1 text-xs font-semibold">
                                                    Repisas: {{ $p->repisas_iluminadas }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 text-right">
                                        <div class="inline-flex flex-wrap justify-end gap-2">
                                            <button type="button"
                                                    @click="open=true"
                                                    class="px-3 py-2 rounded-xl bg-gray-900 text-white hover:bg-gray-800 font-semibold">
                                                Ver
                                            </button>

                                            <a href="{{ route('admin.productos.edit', $p) }}"
                                               class="px-3 py-2 rounded-xl bg-white border border-gray-200 hover:bg-gray-100 font-semibold">
                                                Editar
                                            </a>

                                            <div class="inline-block" x-data="{ del:false }">
                                                <button type="button" @click="del=true"
                                                        class="px-3 py-2 rounded-xl bg-red-50 text-red-700 hover:bg-red-100 font-semibold">
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

                                            {{-- SLIDEOVER PRO --}}
                                            <div x-show="open" x-cloak class="fixed inset-0 z-50">
                                                <div class="absolute inset-0 bg-gray-900/50" @click="open=false"></div>

                                                <div class="absolute right-0 top-0 h-full w-full max-w-[560px] bg-white shadow-2xl border-l border-gray-100 overflow-hidden">
                                                    {{-- Header sticky --}}
                                                    <div class="sticky top-0 z-10 bg-white/95 backdrop-blur border-b border-gray-100">
                                                        <div class="p-6 flex items-start justify-between gap-4">
                                                            <div class="min-w-0">
                                                                <div class="text-[11px] tracking-wide text-gray-500 font-bold uppercase">
                                                                    Detalle del producto
                                                                </div>

                                                                <h3 class="text-xl font-semibold text-gray-900 mt-1 truncate">
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
                                                                    <span class="text-xs text-gray-400 font-semibold">ID #<span x-text="producto.id"></span></span>
                                                                </div>
                                                            </div>

                                                            <button @click="open=false"
                                                                    class="shrink-0 px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 font-semibold">
                                                                Cerrar
                                                            </button>
                                                        </div>
                                                    </div>

                                                    {{-- Body --}}
                                                    <div class="p-6 space-y-6 overflow-y-auto h-[calc(100%-148px)]">
                                                        {{-- KPIs --}}
                                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                                            <div class="rounded-2xl border border-gray-100 bg-gray-50 p-4">
                                                                <div class="text-xs text-gray-500 font-semibold">Refrigerante</div>
                                                                <div class="mt-1 text-base font-semibold text-gray-900" x-text="producto.refrigerante || '—'"></div>
                                                            </div>

                                                            <div class="rounded-2xl border border-gray-100 bg-gray-50 p-4">
                                                                <div class="text-xs text-gray-500 font-semibold">Repisas</div>
                                                                <div class="mt-1 text-base font-semibold text-gray-900" x-text="(producto.repisas_iluminadas ?? '—')"></div>
                                                            </div>

                                                            <div class="rounded-2xl border border-gray-100 bg-gray-50 p-4">
                                                                <div class="text-xs text-gray-500 font-semibold">Dimensiones</div>
                                                                <div class="mt-1 text-sm font-semibold text-gray-900" x-text="dims()"></div>
                                                                <div class="text-[11px] text-gray-500 mt-1">L · P · A</div>
                                                            </div>
                                                        </div>

                                                        {{-- Foto --}}
                                                        <div class="rounded-2xl border border-gray-100 overflow-hidden">
                                                            <div class="px-5 py-4 bg-white border-b border-gray-100 flex items-center justify-between">
                                                                <div>
                                                                    <div class="font-semibold text-gray-900">Foto</div>
                                                                    <div class="text-xs text-gray-500">Vista previa del producto.</div>
                                                                </div>
                                                                <template x-if="producto.foto">
                                                                    <span class="inline-flex items-center rounded-full bg-green-50 text-green-700 px-2.5 py-1 text-xs font-semibold">
                                                                        Disponible
                                                                    </span>
                                                                </template>
                                                            </div>

                                                            <div class="p-5 bg-gray-50">
                                                                <template x-if="producto.foto">
                                                                    <div class="space-y-3">
                                                                        <img :src="producto.foto" alt="Foto del producto"
                                                                             class="w-full max-h-64 object-contain rounded-2xl bg-white border border-gray-100">
                                                                        <div class="text-xs text-gray-600 break-all">
                                                                            <span class="font-semibold">URL:</span>
                                                                            <span x-text="producto.foto"></span>
                                                                        </div>
                                                                    </div>
                                                                </template>

                                                                <template x-if="!producto.foto">
                                                                    <div class="rounded-2xl border border-dashed border-gray-200 bg-white p-6 text-center">
                                                                        <div class="text-sm font-semibold text-gray-900">Sin foto registrada</div>
                                                                        <div class="text-xs text-gray-500 mt-1">Puedes agregarla desde “Editar”.</div>
                                                                    </div>
                                                                </template>
                                                            </div>
                                                        </div>

                                                        {{-- Descripción --}}
                                                        <div class="rounded-2xl border border-gray-100 overflow-hidden">
                                                            <div class="px-5 py-4 bg-white border-b border-gray-100">
                                                                <div class="font-semibold text-gray-900">Descripción</div>
                                                                <div class="text-xs text-gray-500">Información útil para diagnóstico y soporte.</div>
                                                            </div>
                                                            <div class="p-5 bg-white">
                                                                <p class="text-sm text-gray-700 whitespace-pre-line"
                                                                   x-text="producto.descripcion || 'Sin descripción.'"></p>
                                                            </div>
                                                        </div>

                                                        {{-- Registro --}}
                                                        <div class="rounded-2xl border border-gray-100 overflow-hidden">
                                                            <div class="px-5 py-4 bg-white border-b border-gray-100">
                                                                <div class="font-semibold text-gray-900">Registro</div>
                                                            </div>
                                                            <div class="p-5 bg-white grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
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

                                                    {{-- Footer sticky --}}
                                                    <div class="sticky bottom-0 border-t border-gray-100 bg-white/95 backdrop-blur">
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
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-16 text-center text-gray-600">
                                        No hay productos registrados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-4 py-3 border-t border-gray-100">
                    {{ $productos->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
