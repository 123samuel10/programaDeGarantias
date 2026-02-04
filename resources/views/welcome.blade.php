<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistema de Garantías | Heral Enterprises</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css','resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100 text-slate-900">

<main class="min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-lg">

        <div class="rounded-3xl bg-white shadow-lg border border-slate-200 overflow-hidden">

            {{-- HEADER --}}
            <div class="p-8 text-center border-b border-slate-200">
                <div class="mx-auto mb-4 h-12 w-12 rounded-2xl bg-slate-900 text-white grid place-items-center">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none">
                        <path d="M12 2 20 6v6c0 6-8 10-8 10S4 18 4 12V6l8-4Z"
                              stroke="currentColor" stroke-width="2"/>
                        <path d="M9 12l2 2 4-5"
                              stroke="currentColor" stroke-width="2"
                              stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>

                <h1 class="text-3xl font-extrabold tracking-tight">
                    HERAL ENTERPRISES
                </h1>

                <p class="mt-1 text-sm font-semibold text-slate-500">
                    Sistema de Garantías
                </p>
            </div>

            {{-- BODY --}}
            <div class="p-8">
                <p class="text-sm text-slate-600 text-center">
                    Acceso interno para la gestión de garantías y productos.
                </p>

                @if (Route::has('login'))
                    <div class="mt-6 grid gap-3">
                        @auth
                            <a href="{{ url('/dashboard') }}"
                               class="w-full rounded-2xl bg-slate-900 py-3.5 text-center text-sm font-semibold text-white hover:bg-slate-800 transition">
                                Entrar al sistema
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                               class="w-full rounded-2xl bg-slate-900 py-3.5 text-center text-sm font-semibold text-white hover:bg-slate-800 transition">
                                Iniciar sesión
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                   class="w-full rounded-2xl border border-slate-300 py-3.5 text-center text-sm font-semibold text-slate-800 hover:bg-slate-50 transition">
                                    Registrarse
                                </a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>

            {{-- FOOTEER --}}
            <div class="px-8 py-4 border-t border-slate-200 bg-slate-50 text-center">
                <p class="text-xs text-slate-500">
                    Soporte interno · <span class="font-semibold text-slate-700">garantias@heralenterprises.com</span>
                </p>
            </div>

        </div>

        <p class="mt-4 text-center text-xs text-slate-400">
            © {{ date('Y') }} Heral Enterprises · Uso interno
        </p>

    </div>
</main>

</body>
</html>
