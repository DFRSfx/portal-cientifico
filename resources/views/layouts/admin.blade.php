<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Scientific Portal.">
    <link rel="icon" href="{{ asset('logo/icon-portalcientifico.svg') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>
        @php($AppTitle = $title ?? config('app.name', 'Laravel'))

        {{ __(':title', ['title' => $AppTitle]) }}
    </title>

    @stack('header-links')

    <!-- JQuery-->
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>


    @stack('header-scripts')

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="saas min-h-screen flex flex-col justify-between antialiased">

    <x-layouts.nav-bar />

    {{ $slot }}

    <x-scroll-top-button />

    <x-layouts.footer />

    <!-- MDB -->
    @stack('scripts')


</body>

</html>
