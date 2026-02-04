{{-- resources/views/admin/productos/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-start justify-between gap-4">
            <div class="min-w-0">
                <h2 class="text-xl sm:text-2xl font-semibold text-gray-900">Editar producto</h2>
                <p class="text-sm text-gray-600">Actualiza los datos del catálogo.</p>
            </div>

            <a href="{{ route('admin.productos.index') }}"
               class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-900 font-semibold whitespace-nowrap">
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="rounded-2xl border border-green-200 bg-green-50 p-4 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-red-800">
                    <div class="font-semibold">Revisa los campos</div>
                    <ul class="text-sm list-disc pl-5 mt-1">
                        @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.productos.update', $producto) }}"
                  enctype="multipart/form-data"
                  class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                @csrf
                @method('PUT')

                <div class="p-6 border-b border-gray-100 flex items-start justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Producto</h3>
                        <p class="text-sm text-gray-500 mt-1">
                            ID #{{ $producto->id }} · {{ $producto->modelo ?? '—' }}
                        </p>
                    </div>
                </div>

                @include('admin.productos._form', ['producto' => $producto])

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-2">
                    <a href="{{ route('admin.productos.index') }}"
                       class="px-4 py-2 rounded-xl bg-white border border-gray-200 hover:bg-gray-100 font-semibold">
                        Cancelar
                    </a>

                    <button class="px-4 py-2 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700 shadow-sm">
                        Guardar cambios
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
