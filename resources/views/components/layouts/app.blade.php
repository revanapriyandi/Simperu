<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />

    <meta name="application-name" content="{{ config('app.name') }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>{{ config('app.name') }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    <link rel="icon" href="{{ asset('assets/logo.png') }}" type="image/x-icon">
    <link rel="apple-touch-icon" href="{{ asset('assets/logo.png') }}" />

    @filamentStyles
    @vite('resources/css/app.css')
    @stack('styles')
</head>

<body class="antialiased">
    {{ $slot }}


    @filamentScripts
    @vite('resources/js/app.js')
    @stack('scripts')
</body>

</html>
