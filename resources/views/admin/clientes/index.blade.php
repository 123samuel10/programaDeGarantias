{{-- resources/views/admin/clientes/index.blade.php --}}
<x-app-layout>
    {{-- =========================
         HEADER / TOP BAR
    ========================= --}}
    <x-slot name="header">
        <div class="flex flex-col gap-4">
            {{-- Título + CTA --}}
            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                <div class="min-w-0">
                    <div class="flex items-center gap-3 flex-wrap">
                        <h2 class="text-2xl font-semibold text-gray-900">
                            Clientes
                        </h2>

                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                     bg-gray-50 text-gray-700 border border-gray-100">
                            {{ $clientes->total() }} registro(s)
                        </span>

                        @if(request('buscar'))
                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold
                                         bg-blue-50 text-blue-700 border border-blue-100">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M21 21l-4.3-4.3m1.8-5.2a7 7 0 11-14 0 7 7 0 0114 0z"
                                          stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                                Filtro activo
                            </span>
                        @endif
                    </div>

                    <p class="text-sm text-gray-600 mt-1">
                        Busca, gestiona y administra clientes para asociar garantías y seguimientos.
                    </p>
                </div>

                {{-- Botón principal --}}
                <a href="{{ route('admin.clientes.create') }}"
                   class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white
                          hover:bg-blue-700 shadow-sm whitespace-nowrap">
                    <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-white/15">
                        <svg class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </span>
                    Nuevo cliente
                </a>
            </div>

            {{-- =========================
                 BUSCADOR PRO
            ========================= --}}
            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-4">
                <form method="GET" action="{{ route('admin.clientes.index') }}"
                      class="flex flex-col lg:flex-row gap-3 lg:items-center lg:justify-between">

                    {{-- Input + botón integrado --}}
                    <div class="flex-1">
                        <label class="sr-only" for="buscar">Buscar</label>

                        <div class="relative">
                            <span class="absolute inset-y-0 left-4 flex items-center text-gray-400">
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M21 21l-4.3-4.3m1.8-5.2a7 7 0 11-14 0 7 7 0 0114 0z"
                                          stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                            </span>

                            <input id="buscar"
                                   type="text"
                                   name="buscar"
                                   value="{{ request('buscar') }}"
                                   placeholder="Buscar cliente por nombre, empresa, email, teléfono, ciudad o documento…"
                                   class="w-full pl-12 pr-36 py-3 rounded-2xl border-gray-200
                                          focus:border-blue-500 focus:ring-blue-500 text-sm bg-white"/>

                            {{-- Botón buscar dentro del input --}}
                            <div class="absolute inset-y-0 right-2 flex items-center gap-2">
                                @if(request('buscar'))
                                    <a href="{{ route('admin.clientes.index') }}"
                                       class="px-3 py-2 rounded-xl bg-gray-100 text-gray-800 text-sm font-semibold hover:bg-gray-200">
                                        Limpiar
                                    </a>
                                @endif

                                <button type="submit"
                                        class="px-4 py-2 rounded-xl bg-gray-900 text-white text-sm font-semibold hover:bg-gray-800 shadow-sm">
                                    Buscar
                                </button>
                            </div>
                        </div>

                        {{-- Línea inferior: hint --}}
                        <div class="mt-2 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                            <p class="text-xs text-gray-500">
                                Tip: escribe “nit”, “correo”, “empresa”, “medellín”, “300…” y filtra rápido.
                            </p>

                            @if(request('buscar'))
                                <p class="text-xs text-gray-600">
                                    Mostrando resultados para:
                                    <span class="font-semibold text-gray-900">{{ request('buscar') }}</span>
                                </p>
                            @endif
                        </div>
                    </div>

                    {{-- Mini resumen --}}
                    <div class="flex items-center gap-2 justify-end">
                        <div class="hidden lg:block text-xs text-gray-500">
                            Página <span class="font-semibold text-gray-900">{{ $clientes->currentPage() }}</span>
                            de <span class="font-semibold text-gray-900">{{ $clientes->lastPage() }}</span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </x-slot>

    {{-- =========================
         BODY
    ========================= --}}
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- ALERTAS --}}
            @if(session('success'))
                <div class="rounded-2xl border border-green-200 bg-green-50 p-4 text-green-800 flex items-start gap-3">
                    <svg class="w-5 h-5 mt-0.5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M20 6 9 17l-5-5" stroke="currentColor" stroke-width="2.2"
                              stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <div class="text-sm">{{ session('success') }}</div>
                </div>
            @endif

            @if(session('error'))
                <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-red-800 flex items-start gap-3">
                    <svg class="w-5 h-5 mt-0.5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M12 9v4m0 4h.01M10.3 4.3h3.4L21 19H3l7.3-14.7Z"
                              stroke="currentColor" stroke-width="2"
                              stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <div class="text-sm">{{ session('error') }}</div>
                </div>
            @endif

            {{-- TABLA --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-gray-600">
                            <tr>
                                <th class="px-6 py-3 text-left font-semibold">Cliente</th>
                                <th class="px-6 py-3 text-left font-semibold">Contacto</th>
                                <th class="px-6 py-3 text-left font-semibold">Ubicación</th>
                                <th class="px-6 py-3 text-right font-semibold">Acciones</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @forelse($clientes as $c)
                                @php
                                    $nombre  = $c->nombre_contacto ?: 'Cliente';
                                    $empresa = $c->empresa;
                                    $tipo    = $c->tipo_cliente ?? ($empresa ? 'empresa' : 'persona');
                                    $inicial = strtoupper(mb_substr($nombre, 0, 1));

                                    $chipTipo = $tipo === 'empresa'
                                        ? 'bg-indigo-50 text-indigo-700 border-indigo-100'
                                        : 'bg-gray-100 text-gray-700 border-gray-200';
                                @endphp

                                <tr class="hover:bg-gray-50/60 transition">
                                    {{-- Cliente --}}
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-11 h-11 rounded-2xl bg-blue-50 border border-blue-100 flex items-center justify-center shrink-0">
                                                <span class="font-bold text-blue-700">{{ $inicial }}</span>
                                            </div>

                                            <div class="min-w-0">
                                                <div class="flex items-center gap-2 flex-wrap">
                                                    <div class="font-semibold text-gray-900 truncate">
                                                        {{ $nombre }}
                                                    </div>

                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold border {{ $chipTipo }}">
                                                        {{ $tipo === 'empresa' ? 'Empresa' : 'Persona' }}
                                                    </span>
                                                </div>

                                                <div class="text-xs text-gray-500 truncate mt-0.5">
                                                    {{ $empresa ? $empresa : 'Persona natural' }}
                                                </div>

                                                @if($c->documento)
                                                    <div class="text-[11px] text-gray-400 truncate mt-0.5">
                                                        Doc: {{ $c->documento }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Contacto --}}
                                    <td class="px-6 py-4">
                                        <div class="text-gray-900 truncate">{{ $c->email ?? '—' }}</div>
                                        <div class="text-xs text-gray-500 truncate mt-0.5">
                                            {{ $c->telefono ?? '—' }}
                                            @if($c->telefono_alterno)
                                                <span class="text-gray-400">·</span> {{ $c->telefono_alterno }}
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Ubicación --}}
                                    <td class="px-6 py-4">
                                        <div class="text-gray-900 truncate">{{ $c->ciudad ?? '—' }}</div>
                                        <div class="text-xs text-gray-500 truncate mt-0.5">
                                            {{ $c->pais ?? '—' }}
                                            @if($c->direccion)
                                                <span class="text-gray-400">·</span> {{ $c->direccion }}
                                            @endif
                                        </div>
                                    </td>

                                    {{-- =========================
                                         ACCIONES VISIBLES (PRO)
                                    ========================= --}}
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-end gap-2" x-data="{ openDelete:false }">
                                            {{-- Editar --}}
                                            <a href="{{ route('admin.clientes.edit', $c) }}"
                                               class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl
                                                      bg-white border border-gray-200 hover:bg-gray-100 font-semibold text-gray-900">
                                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                    <path d="M12 20h9" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                    <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5Z"
                                                          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                                Editar
                                            </a>

                                            {{-- Eliminar --}}
                                            <button type="button"
                                                    @click="openDelete=true"
                                                    class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl
                                                           bg-red-50 text-red-700 hover:bg-red-100 font-semibold">
                                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                    <path d="M3 6h18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                    <path d="M8 6V4h8v2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                    <path d="M7 6l1 16h8l1-16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                                Eliminar
                                            </button>

                                            {{-- Modal eliminar --}}
                                            <div x-show="openDelete" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
                                                <div class="absolute inset-0 bg-gray-900/50" @click="openDelete=false"></div>

                                                <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden">
                                                    <div class="p-6">
                                                        <div class="flex items-start gap-4">
                                                            <div class="w-12 h-12 rounded-2xl bg-red-50 border border-red-100 flex items-center justify-center shrink-0">
                                                                <svg class="w-6 h-6 text-red-700" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                                    <path d="M12 9v4m0 4h.01M10.3 4.3h3.4L21 19H3l7.3-14.7Z"
                                                                          stroke="currentColor" stroke-width="2"
                                                                          stroke-linecap="round" stroke-linejoin="round"/>
                                                                </svg>
                                                            </div>

                                                            <div class="min-w-0">
                                                                <h4 class="text-lg font-semibold text-gray-900">
                                                                    Confirmar eliminación
                                                                </h4>

                                                                <p class="text-sm text-gray-600 mt-1">
                                                                    Vas a eliminar a:
                                                                    <span class="font-semibold text-gray-900">{{ $nombre }}</span>
                                                                    @if($empresa)
                                                                        <span class="text-gray-500">· {{ $empresa }}</span>
                                                                    @else
                                                                        <span class="text-gray-500">· Persona natural</span>
                                                                    @endif
                                                                </p>

                                                                <div class="mt-4 rounded-xl bg-red-50 border border-red-100 p-3">
                                                                    <p class="text-sm text-red-800">
                                                                        Esta acción <span class="font-semibold">no se puede deshacer</span>.
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-2">
                                                        <button type="button"
                                                                @click="openDelete=false"
                                                                class="px-4 py-2 rounded-xl bg-white border border-gray-200 hover:bg-gray-100 font-semibold">
                                                            Cancelar
                                                        </button>

                                                        <form method="POST" action="{{ route('admin.clientes.destroy', $c) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="px-4 py-2 rounded-xl bg-red-600 text-white font-semibold hover:bg-red-700 shadow-sm">
                                                                Sí, eliminar
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- /Modal --}}
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-16">
                                        <div class="mx-auto max-w-md text-center">
                                            <div class="text-gray-900 font-semibold text-lg">
                                                No hay clientes registrados
                                            </div>
                                            <div class="text-sm text-gray-500 mt-1">
                                                Crea tu primer cliente para empezar a registrar garantías.
                                            </div>

                                            <a href="{{ route('admin.clientes.create') }}"
                                               class="mt-6 inline-flex items-center gap-2 px-4 py-2 rounded-xl
                                                      bg-blue-600 text-white font-semibold hover:bg-blue-700 shadow-sm">
                                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                    <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                </svg>
                                                Crear cliente
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-4 py-3 bg-white border-t border-gray-100">
                    {{ $clientes->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
