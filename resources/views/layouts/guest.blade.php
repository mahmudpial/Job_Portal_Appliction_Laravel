<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700,800&family=space-grotesk:500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-900 antialiased">
        <div class="auth-shell flex min-h-screen flex-col items-center justify-center px-4 py-8 sm:py-12">
            <div>
                <a href="/">
                    <x-application-logo class="h-20 w-20 fill-current text-blue-700" />
                </a>
            </div>

            <div class="auth-shell-card mt-6 w-full overflow-hidden rounded-2xl px-6 py-5 sm:max-w-md">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
