<x-app-layout>

    <x-slot:title>
        Home
    </x-slot>

    <link href="{{ asset('css\extra.css') }}" rel="stylesheet">

    <main>

        <div class="container pt-5">
            <div class="row">
                <div class="col-xl-3">
                    <div class="card testimonial-card mb-3">
                        <div class="card-up">

                        </div>
                        <div class="avatar mx-auto white">
                            <x-user-image
                                showLogedUserImage="{{ Auth::check() && auth()->user()->type != 'administrative' }}"
                                class="rounded-circle img-fluid" height="36" />
                        </div>
                        <div class="card-body" style="height: 250px;">
                            @if (Auth::check())
                                <h5 class="font-weight-bolder mb-1">
                                    {{ auth()->user()->name }}
                                </h5>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="btn btn-rounded gain-bg ripple-surface-dark text-white"
                                        data-mdb-ripple-color="dark" style="background-color:#33642b">
                                        {{ __('Logout') }} </button>
                                </form>
                            @else
                                <h5 class="font-weight-bolder mb-1">{{ __('Convidado') }}</h5>
                                <p>{{ __('Faça login para aceder a todas as funcionalidades!') }}</p>
                                <a href="{{ route('login') }}">
                                    <button type="button"
                                        class="btn btn-rounded gain-bg ripple-surface-dark text-white"
                                        data-mdb-ripple-color="dark" style="background-color:#33642b">
                                        {{ __('Iniciar Sessão') }} </button></a>
                            @endif

                            <div class="small pt-3">{{ __('Aceda a página') }}
                                <a href="{{ route('about.index') }}" style="color:#2A6B20"> {{ __('Sobre Nós') }}
                                </a>{{ __('para obter mais informações') }}
                            </div>

                            <hr class="mt-2 mb-0">

                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <p class="mb-4" style="color:#2A6B20">{{ __('Eventos') }}</p>
                            <div class="list-group list-group-flush">

                                @if(!empty($eventsFinal) && count($eventsFinal))
                                    @foreach ($eventsFinal as $event)
                                        @php
                                            $title = data_get($event, 'title.rendered', data_get($event, 'title', ''));
                                            $title = is_string($title) ? html_entity_decode($title) : '';
                                            $link  = data_get($event, 'link', '#');
                                        @endphp

                                        <a href="{{ $link }}"
                                        class="list-group-item list-group-item-action border-0 py-1 text-truncate"
                                        rel="noopener noreferrer" target="_blank">
                                            <i class="fas fa-calendar pe-2"></i>{{ $title }}
                                        </a>
                                    @endforeach
                                @else
                                    <div class="list-group-item border-0 py-1 text-muted">{{ __('Sem eventos recentes') }}</div>
                                @endif

                            </div>
                            <hr>

                            <a href="https://investigacao.islagaia.pt/events/" target="_blank" rel="noopner noreferrer">
                                <button type="button" class="btn btn-link " style="color:#343a40"
                                    data-mdb-ripple-color="dark"> {{ __('Mostrar todos os Eventos') }} <i
                                        class="fas fa-arrow-right ps-2"></i></button>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-xl-5">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="bg-image hover-overlay ripple rounded-0 ripple-surface-light"
                                data-mdb-ripple-color="light">
                                <img src="{{ asset('logo/bg-portal.avif') }}" alt="Logo POCH" class="img-fluid"
                                    width="auto" height="2.2rem">
                            </div>
                        </div>
                    </div>


                    <div class="card mb-3">
                        <div class="card-body">
                            <p class="mb-4" style="color:#2A6B20">{{ __('Últimos artigos') }}</p>

                            @foreach ($lastOutputs as $publication)
                                <x-publication-title :publication=$publication displayType="0"
                                    displayAccessPubButton="1" />
                                <hr>
                            @endforeach


                            @if (Auth::check())
                                <a href="{{ route('outputs.index') }}">
                                    <button type="button" class="btn btn-link " style="color:#343a40"
                                        data-mdb-ripple-color="dark"> {{ __('Mostrar todas as Publicações') }} <i
                                            class="fas fa-arrow-right ps-2"></i></button>
                                </a>
                            @endif

                        </div>
                    </div>


                </div>
                <!-- Start Section 3-->
                <div class="col-xl-4">
                    <!-- Start Search-->
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 mb-4 mt-4 mb-md-0">
                                    <div class="card">
                                        <div class="p-2">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <div class="p-3 badge-primary rounded-4 "
                                                        style="background-color:#2A6B20">
                                                        <i class="fas fa-user fa-lg fa-fw" style="color:white;"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 ms-4">
                                                    <p class="text-muted mb-1">{{ __('Autores') }}</p>
                                                    <div>
                                                        <h2 class="mb-0 counter" id="number-of-authors"
                                                            data-count="{{ $numberOfAuthors }}">
                                                            {{ $numberOfAuthors }}
                                                        </h2>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 mb-4 mt-4 mb-md-0">
                                    <div class="card">
                                        <div class="p-2">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <div class="p-3 badge-primary rounded-4 "
                                                        style="background-color:#2A6B20">
                                                        <i class="fas fa-newspaper fa-lg fa-fw"
                                                            style="color:white;"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 ms-4">
                                                    <p class="text-muted mb-1">{{ __('Artigos') }}</p>
                                                    <div title="Artigos">
                                                        <h2 class="mb-0 counter" id="number-of-outputs"
                                                            data-count="{{ $numberOfOutputs }}">
                                                            {{ $numberOfOutputs }}
                                                        </h2>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- Section: Design Block -->
                    </div>


                    <!-- Start Ads-->
                    <div class="card mb-3">
                        <div class="card-body">
                            <p class="mb-4" style="color:#2A6B20">{{ __('Links Úteis') }}</p>
                           <div class="d-flex align-items-center w-100 ps-3">
                            <div class="w-100">

                                @if(!empty($coursesFinal) && count($coursesFinal))
                                    @foreach ($coursesFinal as $course)
                                        @php
                                            // data_get funciona quer $course seja array ou object
                                            $name = data_get($course, 'name', data_get($course, 'title.rendered', ''));
                                            $name = is_string($name) ? html_entity_decode($name) : '';
                                            $permalink = data_get($course, 'permalink', data_get($course, 'link', '#'));
                                        @endphp

                                        <a href="{{ $permalink }}"
                                            class="list-group-item list-group-item-action border-0 py-1 text-truncate"
                                            target="_blank" rel="noopener noreferrer">
                                            <i class="fa-solid fa-chalkboard-user pe-2"></i>{{ $name }}
                                        </a>
                                    @endforeach
                                @else
                                    <div class="list-group-item border-0 py-1 text-muted">{{ __('Sem cursos recentes') }}</div>
                                @endif

                                <a href="https://www.webofscience.com/wos/woscc/basic-search"
                                    class="list-group-item list-group-item-action border-0 py-1 text-truncate"
                                    target="_blank" rel="noopener noreferrer">
                                    <i class="fa-solid fa-chalkboard-user pe-2"></i>Web of Science
                                </a>

                            </div>
                        </div>
                            </a>

                            <hr>

                            <a href="https://ci-islagaia.pt/formacao-online/" target="_blank" rel="noopner noreferrer">
                                <button type="button" class="btn btn-link " style="color:#343a40"
                                    data-mdb-ripple-color="dark"> {{ __('Mostrar todos os links úteis') }} <i
                                        class="fas fa-arrow-right ps-2"></i></button>
                            </a>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <script>
        function animateValue(obj, start, end, duration) {
            let startTimestamp = null;

            const step = (timestamp) => {

                // Defines the beginning of the animation
                if (!startTimestamp) startTimestamp = timestamp;

                // Calculates the process
                const progress = Math.min((timestamp - startTimestamp) / duration, 1);

                obj.innerHTML = Math.floor(progress * (end - start) + start);

                // If the process is bigger than one the process will stop
                if (progress < 1) {
                    window.requestAnimationFrame(step);
                }

            };

            // Inicializaes the animation
            window.requestAnimationFrame(step);
        }

        elemtensToAnimate = document.getElementsByClassName("counter");

        // Animates the elemetns that have the classe counter
        Array.from(elemtensToAnimate).forEach(element => {

            // The animation of the numbers ends when they match the maximum value
            const end = element.attributes["data-count"].value;

            animateValue(element, 0, end, 5000);
        });


        /*   function showGrouthRate() {

               if (grouth === null) return;

               // 0 - this year
               // 1 - last year
               const yearsDiff = parseInt(grouth[0]["year"]) - parseInt(grouth[1]["year"]);

               const NumberOfPubsInlastYear = parseInt(grouth[1]["count"]);

               const NumberOfPubsInThisYear = parseInt(grouth[0]["count"]);

               const grouthRate = Math.floor(((NumberOfPubsInThisYear - NumberOfPubsInlastYear) / NumberOfPubsInlastYear) /
                   yearsDiff *
                   100);

               const grouthElement = document.getElementById("pubs-in-two-years");

               if (grouthRate > -1) {
                   grouthElement.innerHTML =
                       `${NumberOfPubsInlastYear + NumberOfPubsInThisYear} <span class="text-success" style="font-size: 0.875rem"> <i class="fas fa-arrow-up fa-sm" id="grouth-symbol"></i><span id="grouth-percentage"> ${grouthRate}% </span></span>`;
               } else {
                   grouthElement.innerHTML =
                       `${NumberOfPubsInlastYear + NumberOfPubsInThisYear} <span class="text-danger" style="font-size: 0.875rem"> <i class="fas fa-arrow-down fa-sm" id="grouth-symbol"></i><span id="grouth-percentage"> ${grouthRate}% </span></span>`;
               }

           }

           showGrouthRate();*/
    </script>

</x-app-layout>
