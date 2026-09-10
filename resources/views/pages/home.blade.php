<x-app-layout>

    <x-slot:title>
        Home
    </x-slot>

    <main>

        <div class="container-xxl saas-dashboard pt-4 pb-4 home-layout">
            <div class="row g-4">
                {{-- Coluna 1: Perfil de Visitante / Utilizador e Eventos --}}
                <div class="col-xl-3">
                    <div class="card testimonial-card mb-4 rounded-2xl border border-slate-200/80 bg-white shadow-sm overflow-hidden">
                        <div class="card-up h-20" style="background-color: #2A6B20;"></div>
                        <div class="avatar mx-auto white relative -mt-10 w-20 h-20 rounded-full border-4 border-white shadow-sm overflow-hidden bg-white flex items-center justify-center">
                            <x-user-image
                                showLogedUserImage="{{ Auth::check() && auth()->user()->type != 'administrative' }}"
                                class="rounded-circle img-fluid w-full h-full object-cover" height="72" />
                        </div>
                        <div class="card-body p-4 text-center">
                            @if (Auth::check())
                                <h5 class="font-bold text-slate-900 mb-2 text-base">
                                    {{ auth()->user()->name }}
                                </h5>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="px-6 py-2 text-xs font-bold text-white uppercase tracking-wider rounded-full shadow-sm hover:shadow transition-all border-0 cursor-pointer"
                                        style="background-color: #2A6B20;">
                                        {{ __('Logout') }} </button>
                                </form>
                            @else
                                <h5 class="font-bold text-slate-900 mb-1 text-base">{{ __('Convidado') }}</h5>
                                <p class="text-xs text-slate-500 mb-3">{{ __('Faça login para aceder a todas as funcionalidades!') }}</p>
                                <a href="{{ route('login') }}">
                                    <button type="button"
                                        class="px-6 py-2 text-xs font-bold text-white uppercase tracking-wider rounded-full shadow-sm hover:shadow transition-all border-0 cursor-pointer"
                                        style="background-color: #2A6B20;">
                                        {{ __('LOGIN') }} </button></a>
                            @endif

                            <div class="small pt-3 text-slate-500">{{ __('Aceda a página') }}
                                <a href="{{ route('about.index') }}" class="font-semibold text-emerald-800 hover:underline"> {{ __('Sobre Nós') }}
                                </a> {{ __('para obter mais informações') }}
                            </div>

                            <hr class="my-3 border-slate-100">

                        </div>
                    </div>

                    <div class="card mb-4 rounded-2xl border border-slate-200/80 bg-white shadow-sm overflow-hidden">
                        <div class="card-body p-4">
                            <p class="mb-3 font-semibold text-sm" style="color:#2A6B20">{{ __('Eventos') }}</p>
                            <div class="list-group list-group-flush space-y-1">

                                @if(!empty($eventsFinal) && count($eventsFinal))
                                    @foreach ($eventsFinal as $event)
                                        @php
                                            $title = data_get($event, 'title.rendered', data_get($event, 'title', ''));
                                            $title = is_string($title) ? html_entity_decode($title) : '';
                                            $link  = data_get($event, 'link', '#');
                                        @endphp

                                        <a href="{{ $link }}"
                                        class="list-group-item list-group-item-action border-0 py-1 text-truncate block text-xs text-slate-700 hover:text-emerald-800"
                                        rel="noopener noreferrer" target="_blank">
                                            <i class="fas fa-calendar pe-2 text-slate-400"></i>{{ $title }}
                                        </a>
                                    @endforeach
                                @else
                                    <div class="list-group-item border-0 py-1 text-slate-400 text-xs">{{ __('Sem eventos recentes') }}</div>
                                @endif

                            </div>
                            <hr class="my-3 border-slate-100">
                            <a href="https://investigacao.islagaia.pt/events/" target="_blank" rel="noopener noreferrer"
                               class="inline-flex items-center text-xs font-bold uppercase tracking-wider text-slate-700 hover:text-emerald-800 transition-colors">
                                {{ __('Mostrar todos os Eventos') }}
                                <i class="fas fa-arrow-right ps-2 text-[10px]"></i>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Coluna 2: Banner Central e Lista de Últimos Artigos --}}
                <div class="col-xl-5">
                    <div class="card mb-4 rounded-2xl border border-slate-200/80 bg-white shadow-sm overflow-hidden">
                        <div class="card-body p-3">
                            <div class="overflow-hidden rounded-xl">
                                <img src="{{ asset('logo/bg-portal.avif') }}" alt="Portal Científico" class="img-fluid rounded-xl w-full h-auto block"
                                    loading="eager">
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4 rounded-2xl border border-slate-200/80 bg-white shadow-sm overflow-hidden">
                        <div class="card-body p-5">
                            <p class="mb-4 font-semibold text-sm" style="color:#2A6B20">{{ __('Últimos artigos') }}</p>

                            @if(!empty($lastOutputs) && count($lastOutputs))
                                @foreach ($lastOutputs as $publication)
                                    <div class="py-1">
                                        <x-publication-title :publication=$publication displayType="0"
                                            displayAccessPubButton="1" />
                                    </div>
                                    @if (!$loop->last)
                                        <hr class="my-3 border-slate-100">
                                    @endif
                                @endforeach
                            @else
                                <p class="text-xs text-slate-400">{{ __('Sem publicações recentes') }}</p>
                            @endif

                            @if (Auth::check())
                                <div class="mt-4 pt-2">
                                    <a href="{{ route('outputs.index') }}"
                                       class="inline-flex items-center text-xs font-bold uppercase tracking-wider text-slate-700 hover:text-emerald-800 transition-colors">
                                        {{ __('Mostrar todas as Publicações') }}
                                        <i class="fas fa-arrow-right ps-2 text-[10px]"></i>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Coluna 3: Estatísticas e Links Úteis --}}
                <div class="col-xl-4">
                    {{-- Estatística Autores --}}
                    <div class="card mb-3 rounded-2xl border border-slate-200/80 bg-white shadow-sm overflow-hidden">
                        <div class="p-4">
                            <div class="flex items-center">
                                <div class="shrink-0">
                                    <div class="w-12 h-12 rounded-xl flex items-center justify-center text-white text-lg shadow-sm"
                                        style="background-color:#2A6B20">
                                        <i class="fas fa-user"></i>
                                    </div>
                                </div>
                                <div class="grow ms-4">
                                    <p class="text-xs text-slate-500 mb-0 font-medium">{{ __('Autores') }}</p>
                                    <div>
                                        <h2 class="mb-0 text-2xl font-bold text-slate-800 counter" id="number-of-authors"
                                            data-count="{{ $numberOfAuthors }}">
                                            {{ $numberOfAuthors }}
                                        </h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Estatística Artigos --}}
                    <div class="card mb-4 rounded-2xl border border-slate-200/80 bg-white shadow-sm overflow-hidden">
                        <div class="p-4">
                            <div class="flex items-center">
                                <div class="shrink-0">
                                    <div class="w-12 h-12 rounded-xl flex items-center justify-center text-white text-lg shadow-sm"
                                        style="background-color:#2A6B20">
                                        <i class="fas fa-newspaper"></i>
                                    </div>
                                </div>
                                <div class="grow ms-4">
                                    <p class="text-xs text-slate-500 mb-0 font-medium">{{ __('Artigos') }}</p>
                                    <div title="{{ __('Artigos') }}">
                                        <h2 class="mb-0 text-2xl font-bold text-slate-800 counter" id="number-of-outputs"
                                            data-count="{{ $numberOfOutputs }}">
                                            {{ $numberOfOutputs }}
                                        </h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Links Úteis --}}
                    <div class="card mb-4 rounded-2xl border border-slate-200/80 bg-white shadow-sm overflow-hidden">
                        <div class="card-body p-4">
                            <p class="mb-3 font-semibold text-sm" style="color:#2A6B20">{{ __('Links Úteis') }}</p>
                            <div class="space-y-1">
                                @if(!empty($coursesFinal) && count($coursesFinal))
                                    @foreach ($coursesFinal as $course)
                                        @php
                                            $name = data_get($course, 'name', data_get($course, 'title.rendered', ''));
                                            $name = is_string($name) ? html_entity_decode($name) : '';
                                            $permalink = data_get($course, 'permalink', data_get($course, 'link', '#'));
                                        @endphp

                                        <a href="{{ $permalink }}"
                                            class="flex items-center py-1.5 text-xs text-slate-700 hover:text-emerald-800 truncate transition-colors"
                                            target="_blank" rel="noopener noreferrer">
                                            <i class="fa-solid fa-chalkboard-user pe-2 text-slate-400 shrink-0"></i>
                                            <span class="truncate">{{ $name }}</span>
                                        </a>
                                    @endforeach
                                @else
                                    <div class="py-1 text-slate-400 text-xs">{{ __('Sem cursos recentes') }}</div>
                                @endif

                                <a href="https://www.webofscience.com/wos/woscc/basic-search"
                                    class="flex items-center py-1.5 text-xs text-slate-700 hover:text-emerald-800 truncate transition-colors"
                                    target="_blank" rel="noopener noreferrer">
                                    <i class="fa-solid fa-chalkboard-user pe-2 text-slate-400 shrink-0"></i>
                                    <span class="truncate">Web of Science</span>
                                </a>
                            </div>

                            <hr class="my-3 border-slate-100">

                            <a href="https://ci-islagaia.pt/formacao-online/" target="_blank" rel="noopener noreferrer"
                               class="inline-flex items-center text-xs font-bold uppercase tracking-wider text-slate-700 hover:text-emerald-800 transition-colors">
                                {{ __('Mostrar todos os links úteis') }}
                                <i class="fas fa-arrow-right ps-2 text-[10px]"></i>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

    {{-- Estilos da Home e carrossel migrados para Tailwind CSS v4 em resources/css/app.css --}}

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
