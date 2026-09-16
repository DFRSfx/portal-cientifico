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
        {{ $title ?? config('app.name', 'Laravel') }}
    </title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />

    @stack('header-links')

    <!-- Jquery -->
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>





    @stack('header-scripts')

    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="saas min-h-screen flex flex-col justify-between antialiased">

    <x-layouts.nav-bar />
    <x-vanilla-modal />

    @if (auth()->check() && !auth()->user()->hasVerifiedEmail() && !request()->routeIs('verification.notice'))
        <x-verify-email-modal />
    @endif

    {{ $slot }}

    <x-scroll-top-button />

    <x-layouts.footer />

    @guest
        <x-auth-modal />
    @endguest

    <!-- MDB -->
    @stack('scripts')

    @livewireScripts

    <script data-navigate-once>
        document.addEventListener("click", function(event) {
            // Report modal trigger
            var link = event.target.closest(".report-link");
            if (link) {
                event.preventDefault();
                var reportModal = document.getElementById("reportModal");
                var reportTitle = document.getElementById("reportModalTitle");
                var reportForm = document.getElementById("reportForm");
                var reportEntity = document.getElementById("reportEntity");
                var entity = link.getAttribute("data-entity") || "";
                var action = link.getAttribute("data-report-action") || (reportForm ? reportForm.action : "");

                if (reportTitle) reportTitle.innerText = "Exportar Relatório - " + entity;
                if (reportForm && action) reportForm.action = action;
                if (reportEntity) reportEntity.value = entity;
                if (reportModal) reportModal.style.display = "flex";
                return;
            }

            // Close modal buttons
            if (event.target.closest("#closeModalBtn") || event.target.closest("#cancelBtn")) {
                var modal = document.getElementById("reportModal");
                if (modal) modal.style.display = "none";
                return;
            }

            // Backdrop click
            var modalBackdrop = document.getElementById("reportModal");
            if (modalBackdrop && event.target === modalBackdrop) {
                modalBackdrop.style.display = "none";
            }
        });
    </script>
</body>

</html>
