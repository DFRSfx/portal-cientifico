<x-app-layout>

    <x-slot:title>
        Autores
    </x-slot>

    <div class="container p-4">
        <div class="col ">
            <div class="row">
                <div class="card">
                    
                    <!--Main layout-->
                    <main class="mb-5" style="margin-top: 58px">
                        <!-- Container for demo purpose -->
                        <div class="container px-4">

                            <!-- Grid row -->
                            <div class="row">
                                <!-- Grid column -->
                                <div class="col-12">
                                    <!--Section: Block Content-->
                                    <section class="my-5 text-center">
                                        <h1 class="text-6xl sm:text-7xl font-extrabold text-emerald-800 mb-2">404</h1>

                                        <h4 class="text-xl font-bold text-slate-800 mb-4">{{ __('Página não encontrada') }}</h4>

                                        <p class="mb-0">
                                            The Page you are looking for doesn't exist or an other error
                                            eccured.
                                        </p>
                                        <p class="mb-4">
                                            Go back, or ahead over to example.com to choose a new direction.
                                        </p>
                                        <a class="btn btn-primary" href="{{ route("home") }}" role="button">Go back to the homepage</a>
                                    </section>
                                    <!--Section: Block Content-->
                                </div>
                                <!-- Grid column -->
                            </div>
                            <!-- Grid row -->

                        </div>
                        <!-- Container for demo purpose -->
                    </main>
                    <!--Main layout-->

                </div>
            </div>
        </div>
    </div>

</x-app-layout>