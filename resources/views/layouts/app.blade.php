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

    <!-- MDB -->
    @stack('scripts')

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Get elements
            var reportLinks = document.querySelectorAll(".report-link");
            var reportModal = document.getElementById("reportModal");
            var closeModalBtns = document.querySelectorAll("#closeModalBtn");
            var reportTitle = document.getElementById("reportModalTitle");
            var reportForm = document.getElementById("reportForm");
            var reportEntity = document.getElementById("reportEntity");

            if (reportLinks.length && reportModal) {
                reportLinks.forEach(function(link) {
                    link.addEventListener("click", function(event) {
                        event.preventDefault();
                        var entity = link.getAttribute("data-entity") || "";
                        var action = link.getAttribute("data-report-action") || reportForm?.action;

                        if (reportTitle) {
                            reportTitle.textContent = entity ? `Gerar relatorio de ${entity}` : 'Gerar relatorio';
                        }

                        if (reportForm && action) {
                            reportForm.action = action;
                        }

                        if (reportEntity) {
                            reportEntity.value = entity;
                        }

                        reportModal.style.display = "flex";
                    });
                });
            }

            // Close the modal when the close button is clicked
            closeModalBtns.forEach(function(btn) {
                btn.addEventListener("click", function() {
                    reportModal.style.display = "none"; // Hide the modal
                });
            });

            // Close the modal when clicking outside the modal content
            reportModal.addEventListener("click", function(event) {
                if (event.target === reportModal) {
                    reportModal.style.display = "none"; // Hide the modal
                }
            });
        });
    </script>
</body>

</html>
