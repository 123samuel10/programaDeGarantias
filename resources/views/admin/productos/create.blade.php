<x-app-layout>
    <x-slot name="header">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h2 class="text-2xl font-semibold text-gray-900">Nuevo producto</h2>
                <p class="text-sm text-gray-600">Registra marca/modelo/descrpción para usar en garantías.</p>
            </div>
            <a href="{{ route('admin.productos.index') }}"
               class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 font-semibold">
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if ($errors->any())
                <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-red-800">
                    <div class="font-semibold">Revisa los campos</div>
                    <ul class="text-sm list-disc pl-5 mt-1">
                        @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.productos.store') }}"
                  class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                @csrf

                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">Datos del producto</h3>
                    <p class="text-sm text-gray-500 mt-1">Modelo = placa/modelo que pide el documento.</p>
                </div>

                @include('admin.productos._form')

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-2">
                    <a href="{{ route('admin.productos.index') }}"
                       class="px-4 py-2 rounded-xl bg-white border border-gray-200 hover:bg-gray-100 font-semibold">
                        Cancelar
                    </a>
                    <button class="px-4 py-2 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700">
                        Guardar
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
