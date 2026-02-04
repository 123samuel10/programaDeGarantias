<x-app-layout>
    <x-slot name="header">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h2 class="text-2xl font-semibold text-gray-900">Productos</h2>
                <p class="text-sm text-gray-600">Catálogo para asociar a garantías.</p>
            </div>

            <a href="{{ route('admin.productos.create') }}"
               class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 shadow-sm">
                + Nuevo producto
            </a>
        </div>

        <div class="mt-4 bg-white border border-gray-100 rounded-2xl shadow-sm p-4">
            <form method="GET" class="flex gap-2">
                <input name="buscar" value="{{ request('buscar') }}"
                       placeholder="Buscar por marca, modelo, tipo o descripción…"
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
                                <th class="px-6 py-3 text-left font-semibold">Marca</th>
                                <th class="px-6 py-3 text-left font-semibold">Tipo</th>
                                <th class="px-6 py-3 text-left font-semibold">Descripción</th>
                                <th class="px-6 py-3 text-right font-semibold">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($productos as $p)
                                <tr class="hover:bg-gray-50/60">
                                    <td class="px-6 py-4 font-semibold text-gray-900">{{ $p->modelo ?? '—' }}</td>
                                    <td class="px-6 py-4">{{ $p->marca ?? '—' }}</td>
                                    <td class="px-6 py-4">{{ $p->tipo_equipo ?? '—' }}</td>
                                    <td class="px-6 py-4">{{ $p->descripcion ?? '—' }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="inline-flex gap-2" x-data="{ open:false }">
                                            <a href="{{ route('admin.productos.edit', $p) }}"
                                               class="px-3 py-2 rounded-xl bg-white border border-gray-200 hover:bg-gray-100 font-semibold">
                                                Editar
                                            </a>
                                            <button type="button" @click="open=true"
                                                    class="px-3 py-2 rounded-xl bg-red-50 text-red-700 hover:bg-red-100 font-semibold">
                                                Eliminar
                                            </button>

                                            <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
                                                <div class="absolute inset-0 bg-gray-900/50" @click="open=false"></div>
                                                <div class="relative w-full max-w-md bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                                                    <div class="p-6">
                                                        <div class="font-semibold text-gray-900">Eliminar producto</div>
                                                        <p class="text-sm text-gray-600 mt-1">
                                                            Modelo: <span class="font-semibold">{{ $p->modelo ?? '—' }}</span>
                                                        </p>
                                                        <div class="mt-4 rounded-xl bg-red-50 border border-red-100 p-3 text-sm text-red-800">
                                                            Esta acción no se puede deshacer.
                                                        </div>
                                                    </div>
                                                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-2">
                                                        <button @click="open=false"
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
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-16 text-center text-gray-600">
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
