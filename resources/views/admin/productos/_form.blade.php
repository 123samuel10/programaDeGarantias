{{-- resources/views/admin/productos/_form.blade.php --}}
@php
    $modoEdicion = isset($producto);
@endphp

<div class="p-6 space-y-8">

    {{-- BLOQUE 1: Identidad del producto --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="lg:col-span-1">
            <h3 class="text-sm font-semibold text-gray-700">Identificación</h3>
            <p class="text-xs text-gray-500 mt-1">
                Lo básico para que el admin lo encuentre y lo asocie a garantías.
            </p>
        </div>

        <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Marca --}}
            <div>
                <label class="text-sm font-medium text-gray-700">Marca *</label>
                <input name="marca"
                       value="{{ old('marca', $modoEdicion ? $producto->marca : '') }}"
                       class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                       placeholder="Ej: Haier"
                       required>
                @error('marca') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Modelo --}}
            <div>
                <label class="text-sm font-medium text-gray-700">Modelo (placa/modelo) *</label>
                <input name="modelo"
                       value="{{ old('modelo', $modoEdicion ? $producto->modelo : '') }}"
                       class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                       placeholder="Ej: MENDOS LU D/M 375 L"
                       required>
                @error('modelo') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Nombre del producto --}}
            <div class="md:col-span-2">
                <label class="text-sm font-medium text-gray-700">Nombre del producto *</label>
                <input name="nombre_producto"
                       value="{{ old('nombre_producto', $modoEdicion ? $producto->nombre_producto : '') }}"
                       class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                       placeholder="Ej: Exhibidor para autoservicio remoto"
                       required>
                @error('nombre_producto') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Tipo de equipo --}}
            <div>
                <label class="text-sm font-medium text-gray-700">Tipo de equipo (opcional)</label>
                <input name="tipo_equipo"
                       value="{{ old('tipo_equipo', $modoEdicion ? ($producto->tipo_equipo ?? '') : '') }}"
                       class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                       placeholder="Ej: Vitrina / Freezer / Nevera">
                @error('tipo_equipo') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Foto (archivo) --}}
            <div class="md:col-span-2">
                <label class="text-sm font-medium text-gray-700">Foto (opcional)</label>

                @if($modoEdicion && !empty($producto->foto))
                    <div class="mt-2 flex items-start gap-4">
                        <img src="{{ asset('storage/'.$producto->foto) }}"
                             class="w-24 h-24 rounded-2xl border border-gray-200 object-cover bg-gray-50"
                             alt="Foto del producto">
                        <div class="text-xs text-gray-500 mt-1">
                            Foto actual. Si subes una nueva, se reemplaza.
                            <div class="mt-1 break-all">Ruta: <span class="font-semibold">{{ $producto->foto }}</span></div>
                        </div>
                    </div>
                @endif

                <input type="file" name="foto" accept="image/*"
                       class="mt-3 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500">

                @error('foto') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                <p class="text-xs text-gray-500 mt-1">Formatos: jpg, png, webp. Máx: 4MB.</p>
            </div>

            {{-- Descripción --}}
            <div class="md:col-span-2">
                <label class="text-sm font-medium text-gray-700">Descripción (opcional)</label>
                <textarea name="descripcion" rows="3"
                          class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                          placeholder="Características generales del equipo…">{{ old('descripcion', $modoEdicion ? $producto->descripcion : '') }}</textarea>
                @error('descripcion') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    {{-- BLOQUE 2: Especificaciones técnicas --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="lg:col-span-1">
            <h3 class="text-sm font-semibold text-gray-700">Especificaciones</h3>
            <p class="text-xs text-gray-500 mt-1">
                Datos técnicos útiles para diagnóstico y seguimiento.
            </p>
        </div>

        <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Repisas iluminadas --}}
            <div>
                <label class="text-sm font-medium text-gray-700">Repisas iluminadas (opcional)</label>
                <input type="number" min="0"
                       name="repisas_iluminadas"
                       value="{{ old('repisas_iluminadas', $modoEdicion ? $producto->repisas_iluminadas : '') }}"
                       class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                       placeholder="Ej: 4">
                @error('repisas_iluminadas') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Refrigerante --}}
            <div>
                <label class="text-sm font-medium text-gray-700">Refrigerante (opcional)</label>
                <input name="refrigerante"
                       value="{{ old('refrigerante', $modoEdicion ? $producto->refrigerante : '') }}"
                       class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                       placeholder="Ej: HFC / R290 / R134a">
                @error('refrigerante') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Medidas --}}
            <div>
                <label class="text-sm font-medium text-gray-700">Longitud (opcional)</label>
                <input type="number" name="longitud"
                       value="{{ old('longitud', $modoEdicion ? $producto->longitud : '') }}"
                       class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                       placeholder="Ej: 120">
                @error('longitud') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="text-sm font-medium text-gray-700">Profundidad (opcional)</label>
                <input type="number" name="profundidad"
                       value="{{ old('profundidad', $modoEdicion ? $producto->profundidad : '') }}"
                       class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                       placeholder="Ej: 60">
                @error('profundidad') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="text-sm font-medium text-gray-700">Altura (opcional)</label>
                <input type="number" name="altura"
                       value="{{ old('altura', $modoEdicion ? $producto->altura : '') }}"
                       class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                       placeholder="Ej: 200">
                @error('altura') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <p class="text-xs text-gray-500 mt-1">
                    Tip: define la unidad (cm) y úsala siempre igual.
                </p>
            </div>
        </div>
    </div>

</div>
