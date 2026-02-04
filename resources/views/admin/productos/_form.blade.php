@php $modoEdicion = isset($producto); @endphp

<div class="p-6 space-y-5">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="text-sm font-medium text-gray-700">Marca</label>
            <input name="marca" value="{{ old('marca', $modoEdicion ? $producto->marca : '') }}"
                   class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500">
            @error('marca') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="text-sm font-medium text-gray-700">Modelo (placa/modelo)</label>
            <input name="modelo" value="{{ old('modelo', $modoEdicion ? $producto->modelo : '') }}"
                   class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500">
            @error('modelo') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="text-sm font-medium text-gray-700">Tipo de equipo</label>
            <input name="tipo_equipo" value="{{ old('tipo_equipo', $modoEdicion ? $producto->tipo_equipo : '') }}"
                   class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500">
            @error('tipo_equipo') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="md:col-span-2">
            <label class="text-sm font-medium text-gray-700">Descripción</label>
            <input name="descripcion" value="{{ old('descripcion', $modoEdicion ? $producto->descripcion : '') }}"
                   class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500">
            @error('descripcion') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
    </div>
</div>
