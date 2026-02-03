{{-- resources/views/admin/clientes/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-2xl text-gray-900">Editar cliente</h2>
                <p class="text-sm text-gray-600">Actualiza datos del cliente para asociar garantías.</p>
            </div>

            <a href="{{ route('admin.clientes.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Alertas --}}
            @if(session('success'))
                <div class="p-4 rounded-xl bg-green-50 border border-green-200 text-green-800 flex items-start gap-3">
                    <svg class="w-5 h-5 mt-0.5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M20 6 9 17l-5-5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <div class="text-sm">{{ session('success') }}</div>
                </div>
            @endif

            @if ($errors->any())
                <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-800">
                    <div class="font-semibold mb-1">Revisa los campos</div>
                    <ul class="text-sm list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.clientes.update', $cliente) }}"
                  class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden"
                  x-data="{ tipo: '{{ old('tipo_cliente', $cliente->tipo_cliente ?? 'persona') }}' }">
                @csrf
                @method('PUT')

                {{-- Header card --}}
                <div class="p-6 border-b border-gray-100">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Información del cliente</h3>
                            <p class="text-sm text-gray-500">Edita datos básicos, contacto y ubicación.</p>
                        </div>

                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                            ID #{{ $cliente->id }}
                        </span>
                    </div>
                </div>

                <div class="p-6 space-y-6">

                    {{-- Tipo de cliente --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-1">
                            <label class="text-sm font-semibold text-gray-700">Tipo de cliente *</label>
                            <p class="text-xs text-gray-500 mt-1">Persona o empresa.</p>
                        </div>

                        <div class="md:col-span-2">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="tipo_cliente" value="persona"
                                           class="peer sr-only" x-model="tipo">
                                    <div class="p-4 rounded-2xl border border-gray-200
                                                peer-checked:border-blue-500 peer-checked:ring-2 peer-checked:ring-blue-100">
                                        <div class="flex items-center justify-between">
                                            <div class="font-semibold text-gray-900">Persona</div>
                                            <span class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-600
                                                         peer-checked:bg-blue-50 peer-checked:text-blue-700">
                                                Natural
                                            </span>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">Cliente particular.</p>
                                    </div>
                                </label>

                                <label class="relative cursor-pointer">
                                    <input type="radio" name="tipo_cliente" value="empresa"
                                           class="peer sr-only" x-model="tipo">
                                    <div class="p-4 rounded-2xl border border-gray-200
                                                peer-checked:border-blue-500 peer-checked:ring-2 peer-checked:ring-blue-100">
                                        <div class="flex items-center justify-between">
                                            <div class="font-semibold text-gray-900">Empresa</div>
                                            <span class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-600
                                                         peer-checked:bg-blue-50 peer-checked:text-blue-700">
                                                Jurídica
                                            </span>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">Distribuidor o negocio.</p>
                                    </div>
                                </label>
                            </div>

                            @error('tipo_cliente')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Identidad --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-1">
                            <label class="text-sm font-semibold text-gray-700">Identidad</label>
                            <p class="text-xs text-gray-500 mt-1">Datos para identificar al cliente.</p>
                        </div>

                        <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-medium text-gray-700">Nombre del contacto *</label>
                                <input name="nombre_contacto"
                                       value="{{ old('nombre_contacto', $cliente->nombre_contacto) }}"
                                       class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                                       placeholder="Ej: Juan Pérez" required>
                                @error('nombre_contacto') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div x-show="tipo === 'empresa'" x-cloak>
                                <label class="text-sm font-medium text-gray-700">Empresa</label>
                                <input name="empresa"
                                       value="{{ old('empresa', $cliente->empresa) }}"
                                       class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                                       placeholder="Ej: Refrigeración ABC S.A.S">
                                @error('empresa') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="text-sm font-medium text-gray-700">Documento (CC / NIT)</label>
                                <input name="documento"
                                       value="{{ old('documento', $cliente->documento) }}"
                                       class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                                       placeholder="Ej: 1234567890">
                                @error('documento') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Contacto --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-1">
                            <label class="text-sm font-semibold text-gray-700">Contacto</label>
                            <p class="text-xs text-gray-500 mt-1">Información para comunicaciones.</p>
                        </div>

                        <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-medium text-gray-700">Correo</label>
                                <input name="email" type="email"
                                       value="{{ old('email', $cliente->email) }}"
                                       class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                                       placeholder="cliente@email.com">
                                @error('email') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="text-sm font-medium text-gray-700">Teléfono</label>
                                <input name="telefono"
                                       value="{{ old('telefono', $cliente->telefono) }}"
                                       class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                                       placeholder="Ej: +57 300 000 0000">
                                @error('telefono') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="text-sm font-medium text-gray-700">Teléfono alterno</label>
                                <input name="telefono_alterno"
                                       value="{{ old('telefono_alterno', $cliente->telefono_alterno) }}"
                                       class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                                       placeholder="Opcional">
                                @error('telefono_alterno') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Ubicación --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-1">
                            <label class="text-sm font-semibold text-gray-700">Ubicación</label>
                            <p class="text-xs text-gray-500 mt-1">Dirección del cliente.</p>
                        </div>

                        <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-medium text-gray-700">País</label>
                                <input name="pais"
                                       value="{{ old('pais', $cliente->pais ?? 'Colombia') }}"
                                       class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                                @error('pais') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="text-sm font-medium text-gray-700">Ciudad</label>
                                <input name="ciudad"
                                       value="{{ old('ciudad', $cliente->ciudad) }}"
                                       class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                                @error('ciudad') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="text-sm font-medium text-gray-700">Dirección</label>
                                <input name="direccion"
                                       value="{{ old('direccion', $cliente->direccion) }}"
                                       class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                                       placeholder="Ej: Calle 10 # 20 - 30">
                                @error('direccion') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Notas --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-1">
                            <label class="text-sm font-semibold text-gray-700">Notas</label>
                            <p class="text-xs text-gray-500 mt-1">Información interna (opcional).</p>
                        </div>

                        <div class="md:col-span-2">
                            <textarea name="notas" rows="4"
                                      class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                                      placeholder="Ej: Cliente frecuente, solicita soporte rápido, etc.">{{ old('notas', $cliente->notas) }}</textarea>
                            @error('notas') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                </div>

                {{-- Footer actions --}}
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                    <p class="text-xs text-gray-500">* Campos obligatorios</p>

                    <div class="flex gap-2">
                        <a href="{{ route('admin.clientes.index') }}"
                           class="px-4 py-2 rounded-xl bg-white border border-gray-200 hover:bg-gray-100 font-semibold">
                            Cancelar
                        </a>

                        <button class="px-4 py-2 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700 shadow-sm">
                            Guardar cambios
                        </button>
                    </div>
                </div>
            </form>

            {{-- Zona peligrosa --}}
            <div class="bg-white rounded-2xl shadow-sm border border-red-100 overflow-hidden"
                 x-data="{ openDelete: false }">
                <div class="p-6 flex items-start justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Zona peligrosa</h3>
                        <p class="text-sm text-gray-600 mt-1">
                            Eliminar este cliente quitará su registro. (Recomendado solo si estás seguro).
                        </p>
                    </div>

                    <button type="button"
                            @click="openDelete = true"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl
                                   bg-red-600 text-white font-semibold hover:bg-red-700 shadow-sm">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M6 7h12M10 7V5h4v2m-7 0 1 14h10l1-14"
                                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Eliminar cliente
                    </button>
                </div>

                {{-- Modal eliminar --}}
                <div x-show="openDelete" x-cloak
                     class="fixed inset-0 z-50 flex items-center justify-center p-4">
                    {{-- Backdrop --}}
                    <div class="absolute inset-0 bg-gray-900/50"
                         @click="openDelete = false"></div>

                    {{-- Panel --}}
                    <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-start gap-4">
                                <div class="w-11 h-11 rounded-2xl bg-red-50 border border-red-100 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-red-700" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <path d="M12 9v4m0 4h.01M10.3 4.3h3.4L21 19H3l7.3-14.7Z"
                                              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>

                                <div class="min-w-0">
                                    <h4 class="text-lg font-semibold text-gray-900">Eliminar cliente</h4>
                                    <p class="text-sm text-gray-600 mt-1">
                                        Vas a eliminar a:
                                        <span class="font-semibold text-gray-900">
                                            {{ $cliente->nombre_contacto }}
                                        </span>
                                        @if($cliente->empresa)
                                            <span class="text-gray-500">· {{ $cliente->empresa }}</span>
                                        @endif
                                    </p>

                                    <div class="mt-4 rounded-xl bg-red-50 border border-red-100 p-3">
                                        <p class="text-sm text-red-800">
                                            Esta acción <span class="font-semibold">no se puede deshacer</span>.
                                            Si este cliente tiene garantías asociadas, podrías romper relaciones (depende de cómo lo manejes).
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-2">
                            <button type="button"
                                    @click="openDelete = false"
                                    class="px-4 py-2 rounded-xl bg-white border border-gray-200 hover:bg-gray-100 font-semibold">
                                Cancelar
                            </button>

                            <form method="POST" action="{{ route('admin.clientes.destroy', $cliente) }}">
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

        </div>
    </div>
</x-app-layout>
