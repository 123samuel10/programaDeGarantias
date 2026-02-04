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
                       placeholder="Ej: Heral"
                       required>
                @error('marca') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Modelo --}}
            <div>
                <label class="text-sm font-medium text-gray-700">Modelo (placa/modelo) *</label>
                <input name="modelo"
                       value="{{ old('modelo', $modoEdicion ? $producto->modelo : '') }}"
                       class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                       placeholder="Ej: NEF-RED201"
                       required>
                @error('modelo') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Nombre del producto --}}
            <div class="md:col-span-2">
                <label class="text-sm font-medium text-gray-700">Nombre del producto *</label>
                <input name="nombre_producto"
                       value="{{ old('nombre_producto', $modoEdicion ? $producto->nombre_producto : '') }}"
                       class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                       placeholder="Ej: Nevera exhibidora 2 puertas"
                       required>
                @error('nombre_producto') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Tipo de equipo --}}
            <div>
                <label class="text-sm font-medium text-gray-700">Tipo de equipo (opcional)</label>
                <input name="tipo_equipo"
                       value="{{ old('tipo_equipo', $modoEdicion ? $producto->tipo_equipo : '') }}"
                       class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                       placeholder="Ej: Vitrina / Freezer / Nevera">
                @error('tipo_equipo') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Foto --}}
            <div>
                <label class="text-sm font-medium text-gray-700">Foto (URL opcional)</label>
                <input name="foto"
                       value="{{ old('foto', $modoEdicion ? $producto->foto : '') }}"
                       class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                       placeholder="Ej: https://...">
                @error('foto') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                <p class="text-xs text-gray-500 mt-1">Tu controlador valida foto como string (por ahora URL o texto).</p>
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
                       placeholder="Ej: R290 / R134a">
                @error('refrigerante') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Medidas --}}
            <div>
                <label class="text-sm font-medium text-gray-700">Longitud (opcional)</label>
                <input type="number"
                       name="longitud"
                       value="{{ old('longitud', $modoEdicion ? $producto->longitud : '') }}"
                       class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                       placeholder="Ej: 120">
                @error('longitud') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="text-sm font-medium text-gray-700">Profundidad (opcional)</label>
                <input type="number"
                       name="profundidad"
                       value="{{ old('profundidad', $modoEdicion ? $producto->profundidad : '') }}"
                       class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                       placeholder="Ej: 60">
                @error('profundidad') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="text-sm font-medium text-gray-700">Altura (opcional)</label>
                <input type="number"
                       name="altura"
                       value="{{ old('altura', $modoEdicion ? $producto->altura : '') }}"
                       class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                       placeholder="Ej: 200">
                @error('altura') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <p class="text-xs text-gray-500 mt-1">
                    Tip: define la unidad (cm / mm) en tu equipo y úsala siempre igual.
                </p>
            </div>

        </div>
    </div>

</div>
