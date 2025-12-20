<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>

        </div>
        <footer class="border-t mt-8 py-4">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-2 text-xs text-gray-500">
                <div>
                    {{-- Footer text từ settings, fallback mặc định --}}
                    {{ setting('footer_text', '© ' . date('Y').' by ' . ' ' . setting('company_name', config('app.name'))) }}
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    @if (setting('support_email'))
                        <span>Email: {{ setting('support_email') }}</span>
                    @endif

                    @if (setting('hotline'))
                        <span>Hotline: {{ setting('hotline') }}</span>
                    @endif
                </div>
            </div>
        </footer>

    </body>
</html>
