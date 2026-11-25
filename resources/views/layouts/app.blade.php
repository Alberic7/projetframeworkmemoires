<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50 text-slate-900 antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title . ' - ' : '' }}{{ config('app.name', 'Gestion des Mémoires') }}</title>

    <!-- Polices / Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Scripts & Styles via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="flex min-h-full flex-col font-sans bg-slate-50 text-slate-800">
    <!-- Barre de navigation -->
    <x-navbar />

    <!-- Messages Flash / Alertes globales -->
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 mt-4">
        <x-alert />
    </div>

    <!-- Contenu Principal -->
    <main class="flex-1 py-6 sm:py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            {{ $slot ?? '' }}
            @yield('content')
        </div>
    </main>

    <!-- Pied de page -->
    <x-footer />

    @stack('scripts')
</body>
</html>
