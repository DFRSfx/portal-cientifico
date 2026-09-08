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
        {{ $title ?? config('app.name', 'Laravel') }}
    </title>

    <!-- Mdb Plugins -->
    <link href="{{ asset('bootstrap\plugins\css\all.min.css') }}" default rel="stylesheet">

    <!-- MDB min css -->
    <link href="{{ asset('bootstrap\css\mdb.min.css') }}" default rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />

    <!-- Custom theme overrides -->
    <link href="{{ asset('css/extra.css') }}" rel="stylesheet">

    @stack('header-links')

    <!-- MDB Min javascript -->
    <script src="{{ asset('bootstrap\js\mdb.min.js') }}" defer></script>

    <!-- MDB PLugins -->
    <script src="{{ asset('bootstrap\plugins\js\all.min.js') }}" defer></script>


    <!-- Jquery -->
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>

    <!-- Alpine.js (used by x-modal component) -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>




    <meta name="csrf-token" content="{{ csrf_token() }}" />

    @stack('header-scripts')

    @vite(['resources/css/app.css', 'resources/js/app.js'])

<body class="saas">

    <x-layouts.nav-bar />
    <x-vanilla-modal />


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
