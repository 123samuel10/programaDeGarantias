<x-app-layout>
    <x-slot name="header">
        <div class="flex items-start justify-between gap-4">
            <div class="min-w-0">
                <h2 class="text-xl sm:text-2xl font-semibold text-gray-900">Nueva garantía</h2>
                <p class="text-sm text-gray-600">Norma: 18 meses desde entrega en fábrica.</p>
            </div>

            <a href="{{ route('admin.garantias.index') }}"
               class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-900 font-semibold whitespace-nowrap">
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if ($errors->any())
                <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-red-800">
                    <div class="font-semibold">Revisa los campos</div>
                    <ul class="text-sm list-disc pl-5 mt-1">
                        @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.garantias.store') }}"
                  class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                @csrf

                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">Información de la garantía</h3>
                    <p class="text-sm text-gray-500 mt-1">El vencimiento se calcula automáticamente.</p>
                </div>

                @include('admin.garantias._form', ['clientes' => $clientes, 'productos' => $productos])

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                    <p class="text-xs text-gray-500">* Campos obligatorios</p>

                    <div class="flex gap-2">
                        <a href="{{ route('admin.garantias.index') }}"
                           class="px-4 py-2 rounded-xl bg-white border border-gray-200 hover:bg-gray-100 font-semibold">
                            Cancelar
                        </a>

                        <button class="px-4 py-2 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700 shadow-sm">
                            Guardar garantía
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
