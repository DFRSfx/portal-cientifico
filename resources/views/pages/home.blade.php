<x-app-layout>

    <x-slot:title>
        Home
    </x-slot>

    <main>

        <div class="container-xxl saas-dashboard pt-4 pb-4 home-layout">
            <div class="row g-4">
                <div class="col-xl-3">
                    <div class="card testimonial-card mb-4">
                        <div class="card-up">

                        </div>
                        <div class="avatar mx-auto white">
                            <x-user-image
                                showLogedUserImage="{{ Auth::check() && auth()->user()->type != 'administrative' }}"
                                class="rounded-circle img-fluid" height="36" />
                        </div>
                        <div class="card-body">
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
                    <div class="card mb-4">
                        <div class="card-body">
                            <p class="mb-2 fw-semibold" style="color:#2A6B20">{{ __('Eventos') }}</p>
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
                                <button type="button" class="btn btn-link" style="color:#343a40"
                                    data-mdb-ripple-color="dark"> {{ __('Mostrar todos os Eventos') }} <i
                                        class="fas fa-arrow-right ps-2"></i></button>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-xl-5">
                    @php
                        $featuredItems = !empty($lastOutputs) ? collect($lastOutputs)->values() : collect();
                        if ($featuredItems->count()) {
                            $targetCount = 5;
                            $cursor = 0;
                            while ($featuredItems->count() < $targetCount) {
                                $featuredItems->push($featuredItems[$cursor % $featuredItems->count()]);
                                $cursor++;
                            }
                            $featuredItems = $featuredItems->take($targetCount);
                        }
                    @endphp
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="bg-image hover-overlay ripple rounded-0 ripple-surface-light"
                                data-mdb-ripple-color="light">
                                <img src="{{ asset('logo/bg-portal.avif') }}" alt="Logo POCH" class="img-fluid"
                                    width="auto" height="2.2rem">
                            </div>
                        </div>
                    </div>

                    @if ($featuredItems->count())
                        <div class="card mb-4 featured-carousel-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <p class="mb-2 fw-semibold" style="color:#2A6B20">{{ __('Últimos artigos') }}</p>
                                    </div>
                                </div>

                                <div id="featured-carousel" class="carousel slide" data-mdb-ride="carousel" data-mdb-interval="4000">
                                    <div class="carousel-inner">
                                        @foreach ($featuredItems as $index => $publication)
                                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                                <div class="p-3 rounded featured-carousel-item">
                                                    <x-publication-title :publication=$publication displayType="0" displayAccessPubButton="1" />
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    @if ($featuredItems->count() > 1)
                                        <div class="carousel-indicators">
                                            @foreach ($featuredItems as $index => $publication)
                                                <button type="button" data-mdb-target="#featured-carousel"
                                                    data-mdb-slide-to="{{ $index }}"
                                                    class="{{ $index === 0 ? 'active' : '' }}"
                                                    aria-label="{{ __('Slide') }} {{ $index + 1 }}"></button>
                                            @endforeach
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <!-- Start Section 3-->
                <div class="col-xl-4">
                    <!-- Start Search-->
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="saas-stat">
                                        <div class="saas-stat-icon"><i class="fas fa-user"></i></div>
                                        <div class="saas-stat-body">
                                            <div class="saas-stat-label">{{ __('Autores') }}</div>
                                            <div class="saas-stat-value counter" id="number-of-authors" data-count="{{ $numberOfAuthors }}">{{ $numberOfAuthors }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="saas-stat">
                                        <div class="saas-stat-icon"><i class="fas fa-newspaper"></i></div>
                                        <div class="saas-stat-body">
                                            <div class="saas-stat-label">{{ __('Artigos') }}</div>
                                            <div class="saas-stat-value counter" id="number-of-outputs" data-count="{{ $numberOfOutputs }}">{{ $numberOfOutputs }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Section: Design Block -->
                    </div>


                    <!-- Start Ads-->
                    <div class="card mb-4">
                        <div class="card-body">
                            <p class="mb-2 fw-semibold" style="color:#2A6B20">{{ __('Links Úteis') }}</p>
                           <div class="d-flex align-items-center w-100 ps-3">
                            <div class="w-100">

                                <p class="text-uppercase small text-muted mb-2">{{ __('Recursos') }}</p>

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

                                <p class="text-uppercase small text-muted mt-3 mb-2">{{ __('Pesquisa') }}</p>
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

    <style>
        .home-layout {
            max-width: 1440px;
        }

        .home-layout .card {
            border: 1px solid #e4efe7;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06);
        }
        .featured-carousel-card {
            background: linear-gradient(135deg, #f3fbf7 0%, #ffffff 60%);
            position: relative;
            overflow: hidden;
        }

        .featured-carousel-card::before {
            content: "";
            position: absolute;
            inset: -40% auto auto -30%;
            width: 260px;
            height: 260px;
            background: radial-gradient(circle, rgba(46, 125, 50, 0.12), rgba(46, 125, 50, 0));
        }

        .featured-carousel-card .card-body {
            position: relative;
        }

        .featured-carousel-item {
            background: #f7fbf8;
            border: 1px solid #e4efe7;
            padding-bottom: 22px;
        }

        .featured-carousel-card .carousel-indicators {
            position: static;
            margin-top: 12px;
        }

        .featured-carousel-card .carousel-indicators [data-mdb-target] {
            background-color: #2a6b20;
            opacity: 0.25;
        }

        .featured-carousel-card .carousel-indicators .active {
            opacity: 0.85;
        }

        .featured-carousel-card .carousel-control-prev-icon,
        .featured-carousel-card .carousel-control-next-icon {
            filter: invert(30%) sepia(12%) saturate(350%) hue-rotate(85deg);
        }

        @media (max-width: 1199.98px) {
            .home-layout {
                max-width: 100%;
            }
        }
    </style>

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
