<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3">
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <h2 class="text-xl sm:text-2xl font-semibold text-gray-900">Garantías</h2>
                    <p class="text-sm text-gray-600">Registra, controla estados y da seguimiento a casos.</p>
                </div>

                <a href="{{ route('admin.garantias.create') }}"
                   class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-3.5 py-2 text-sm font-semibold text-white hover:bg-blue-700 shadow-sm">
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-white/15">
                        <svg class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="none">
                            <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </span>
                    Nueva garantía
                </a>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-4">
                <form method="GET" action="{{ route('admin.garantias.index') }}"
                      class="flex flex-col lg:flex-row gap-3 lg:items-center lg:justify-between">
                    <div class="flex-1">
                        <div class="relative">
                            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                                    <path d="M21 21l-4.3-4.3m1.8-5.2a7 7 0 11-14 0 7 7 0 0114 0z"
                                          stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                            </span>
                            <input name="buscar" value="{{ request('buscar') }}"
                                   placeholder="Buscar por serie, estado, cliente, empresa, documento…"
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
                    <svg class="w-5 h-5 mt-0.5" viewBox="0 0 24 24" fill="none">
                        <path d="M20 6 9 17l-5-5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <div class="text-sm">{{ session('success') }}</div>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-gray-600">
                            <tr>
                                <th class="px-5 py-3 text-left font-semibold">Serie</th>
                                <th class="px-5 py-3 text-left font-semibold">Cliente</th>
                                <th class="px-5 py-3 text-left font-semibold">Fechas</th>
                                <th class="px-5 py-3 text-left font-semibold">Estado</th>
                                <th class="px-5 py-3 text-right font-semibold">Acciones</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @forelse($garantias as $g)
                                @php
                                    $cliente = $g->cliente;
                                    $nombre = $cliente?->nombre_contacto ?? '—';
                                    $empresa = $cliente?->empresa;
                                    $badge = [
                                        'activa' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                        'enproceso' => 'bg-blue-50 text-blue-700 border-blue-100',
                                        'vencida' => 'bg-amber-50 text-amber-800 border-amber-100',
                                        'cerrada' => 'bg-gray-100 text-gray-700 border-gray-200',
                                        'rechazada' => 'bg-red-50 text-red-700 border-red-100',
                                    ][$g->estado] ?? 'bg-gray-100 text-gray-700 border-gray-200';
                                @endphp

                                <tr class="hover:bg-gray-50/60 transition">
                                    <td class="px-5 py-4">
                                        <div class="font-semibold text-gray-900">{{ $g->numero_serie }}</div>
                                        <div class="text-xs text-gray-500">ID: {{ $g->id }}</div>
                                    </td>

                                    <td class="px-5 py-4">
                                        <div class="font-semibold text-gray-900 truncate">{{ $nombre }}</div>
                                        <div class="text-xs text-gray-500 truncate">
                                            {{ $empresa ? $empresa : 'Persona natural' }}
                                        </div>
                                    </td>

                                    <td class="px-5 py-4">
                                        <div class="text-gray-900">Compra: {{ optional($g->fecha_compra)->format('Y-m-d') }}</div>
                                        <div class="text-xs text-gray-500">Vence: {{ optional($g->fecha_vencimiento)->format('Y-m-d') }}</div>
                                    </td>

                                    <td class="px-5 py-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border {{ $badge }}">
                                            {{ strtoupper($g->estado) }}
                                        </span>
                                    </td>

                              <td class="px-5 py-4 text-right">
    <div class="inline-flex gap-2" x-data="{ openDelete:false }">
        {{-- Ver --}}
        <a href="{{ route('admin.garantias.show', $g) }}"
           class="px-3 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 font-semibold">
            Ver caso
        </a>

        {{-- Editar --}}
        <a href="{{ route('admin.garantias.edit', $g) }}"
           class="px-3 py-2 rounded-xl bg-white border border-gray-200 hover:bg-gray-100 font-semibold">
            Editar
        </a>

        {{-- Eliminar --}}
        <button type="button"
                @click="openDelete=true"
                class="px-3 py-2 rounded-xl bg-red-600 text-white font-semibold hover:bg-red-700 shadow-sm">
            Eliminar
        </button>

        {{-- ================= MODAL ELIMINAR ================= --}}
        <div x-show="openDelete" x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center p-4">

            {{-- Overlay --}}
            <div class="absolute inset-0 bg-gray-900/50"
                 @click="openDelete=false"></div>

            {{-- Modal --}}
            <div class="relative w-full max-w-md bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">

                <div class="p-6">
                    <div class="flex items-start gap-4">
                        <div class="w-11 h-11 rounded-2xl bg-red-50 border border-red-100
                                    flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 text-red-700" viewBox="0 0 24 24" fill="none">
                                <path d="M12 9v4m0 4h.01M10.3 4.3h3.4L21 19H3l7.3-14.7Z"
                                      stroke="currentColor" stroke-width="2"
                                      stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>

                        <div class="min-w-0">
                            <h4 class="text-lg font-semibold text-gray-900">
                                Eliminar caso de garantía
                            </h4>

                            <p class="text-sm text-gray-600 mt-1">
                                Vas a eliminar el caso con serie:
                                <span class="font-semibold text-gray-900">
                                    {{ $g->numero_serie }}
                                </span>
                            </p>

                            <div class="mt-4 rounded-xl bg-red-50 border border-red-100 p-3">
                                <p class="text-sm text-red-800">
                                    Esta acción <span class="font-semibold">no se puede deshacer</span>.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100
                            flex items-center justify-end gap-2">
                    <button type="button"
                            @click="openDelete=false"
                            class="px-4 py-2 rounded-xl bg-white border border-gray-200
                                   hover:bg-gray-100 font-semibold">
                        Cancelar
                    </button>

                    <form method="POST" action="{{ route('admin.garantias.destroy', $g) }}">
                        @csrf
                        @method('DELETE')
                        <button
                            class="px-4 py-2 rounded-xl bg-red-600 text-white
                                   font-semibold hover:bg-red-700 shadow-sm">
                            Sí, eliminar
                        </button>
                    </form>
                </div>
            </div>
        </div>
        {{-- =============== /MODAL =============== --}}
    </div>
</td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-14 text-center">
                                        <div class="font-semibold text-gray-900">No hay garantías registradas</div>
                                        <div class="text-sm text-gray-500 mt-1">Crea la primera garantía para iniciar el control.</div>
                                        <a href="{{ route('admin.garantias.create') }}"
                                           class="mt-5 inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700 shadow-sm">
                                            Nueva garantía
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-4 py-3 bg-white border-t border-gray-100">
                    {{ $garantias->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
