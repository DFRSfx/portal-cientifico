<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Scientific Portal.">
    <link rel="icon" href="{{ asset('logo\icon-portalcientifico.svg') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>
        @php($AppTitle = $title ?? config('app.name', 'Laravel'))

        {{ __(':title', ['title' => $AppTitle]) }}
    </title>

    <link href="{{ asset('bootstrap\css\mdb.min.css') }}" rel="stylesheet">

    <!-- Custom theme overrides -->
    <link href="{{ asset('css/extra.css') }}" rel="stylesheet">

    @stack('header-links')

    <!--mdboostrap js-->
    <script src="{{ asset('bootstrap\js\mdb.min.js') }}" defer></script>

    <!-- JQuery-->
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>

    @stack('header-scripts')

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="saas">

    <x-layouts.nav-bar />

    {{ $slot }}

    @if (auth()->check() && !auth()->user()->hasVerifiedEmail() && !request()->routeIs('verification.notice'))
        <x-verify-email-modal />
    @endif
    
    <x-layouts.footer />

</body>

</html>
