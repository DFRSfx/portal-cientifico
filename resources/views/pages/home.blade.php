<x-app-layout>

    <x-slot:title>
        Home
    </x-slot>

    <main>

        <div class="container-xxl saas-dashboard pt-6 sm:pt-8 pb-10 sm:pb-14 home-layout">
            <div class="row g-4 lg:g-5">
                {{-- Coluna 1: Perfil de Visitante / Utilizador e Eventos --}}
                <div class="col-xl-3">
                    <div class="card testimonial-card mb-4 sm:mb-5 rounded-2xl border border-slate-200/80 bg-white shadow-sm overflow-hidden">
                        <div class="card-up h-24 sm:h-28 bg-emerald-800"></div>
                        <div class="avatar mx-auto white relative -mt-12 w-24 h-24 rounded-full border-4 border-white shadow-md overflow-hidden bg-white flex items-center justify-center">
                            <x-user-image
                                showLogedUserImage="{{ Auth::check() && auth()->user()->type != 'administrative' }}"
                                class="rounded-circle img-fluid w-full h-full object-cover" height="88" width="88" />
                        </div>
                        <div class="card-body p-6 sm:p-7 text-center">
                            @if (Auth::check())
                                <h4 class="font-bold text-slate-900 mb-2 text-lg sm:text-xl">
                                    {{ auth()->user()->name }}
                                </h4>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="px-8 py-2.5 text-xs sm:text-sm font-bold text-white uppercase tracking-wider rounded-xl shadow-xs hover:shadow transition-all border-0 cursor-pointer bg-emerald-700 hover:bg-emerald-800">
                                        {{ __('Logout') }} </button>
                                </form>
                            @else
                                <h4 class="font-semibold text-slate-900 mb-1.5 text-lg sm:text-xl">{{ __('Convidado') }}</h4>
                                <p class="text-xs sm:text-sm text-slate-500 mb-4">{{ __('Faça login para aceder a todas as funcionalidades!') }}</p>
                                <button type="button"
                                    onclick="if (typeof openAuthModal === 'function') { openAuthModal('login'); } else { window.location.href = '{{ route('login') }}'; }"
                                    class="px-8 py-2.5 text-xs sm:text-sm font-bold text-white uppercase tracking-wider rounded-xl shadow-xs hover:shadow transition-all border-0 cursor-pointer bg-emerald-700 hover:bg-emerald-800">
                                    {{ __('LOGIN') }}
                                </button>
                            @endif

                            <div class="text-xs sm:text-sm pt-4 text-slate-500 leading-relaxed">{{ __('Aceda a página') }}
                                <a href="{{ route('about.index') }}" class="font-semibold text-emerald-800 hover:underline"> {{ __('Sobre Nós') }}
                                </a> {{ __('para obter mais informações') }}
                            </div>

                        </div>
                    </div>

                    <div class="card mb-4 sm:mb-5 rounded-2xl border border-slate-200/80 bg-white shadow-sm overflow-hidden">
                        <div class="card-body p-6 sm:p-7">
                            <h3 class="mb-4 text-base sm:text-lg text-emerald-800 flex items-center gap-2">
                                <i class="fas fa-calendar-days text-emerald-600"></i>
                                <span>{{ __('Eventos') }}</span>
                            </h3>
                            <div class="list-group list-group-flush space-y-1.5">

                                @if(!empty($eventsFinal) && count($eventsFinal))
                                    @foreach ($eventsFinal as $event)
                                        @php
                                            $title = data_get($event, 'title.rendered', data_get($event, 'title', ''));
                                            $title = is_string($title) ? html_entity_decode($title) : '';
                                            $link  = data_get($event, 'link', '#');
                                        @endphp

                                        <a href="{{ $link }}"
                                        class="list-group-item list-group-item-action border-0 py-2 px-2 rounded-lg text-truncate flex items-center gap-2 text-xs sm:text-sm font-medium text-slate-700 hover:text-emerald-800 hover:bg-emerald-50/50 transition-colors"
                                        rel="noopener noreferrer" target="_blank">
                                            <i class="fas fa-calendar text-slate-400 shrink-0 text-sm"></i>
                                            <span class="truncate">{{ $title }}</span>
                                        </a>
                                    @endforeach
                                @else
                                    <div class="list-group-item border-0 py-2 text-slate-400 text-xs sm:text-sm">{{ __('Sem eventos recentes') }}</div>
                                @endif

                            </div>
                            <hr class="my-4 border-slate-100">
                            <a href="https://investigacao.islagaia.pt/events/" target="_blank" rel="noopener noreferrer"
                               class="inline-flex items-center text-xs sm:text-sm font-semibold uppercase tracking-wider text-slate-700 hover:text-emerald-800 transition-colors gap-2 pt-1">
                                <span>{{ __('Mostrar todos os Eventos') }}</span>
                                <i class="fas fa-arrow-right text-xs"></i>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Coluna 2: Banner Central e Lista de Últimos Artigos --}}
                <div class="col-xl-5">
                    <div class="card mb-4 sm:mb-5 rounded-2xl border border-slate-200/80 bg-white shadow-sm overflow-hidden">
                        <div class="card-body p-2.5 sm:p-3">
                            <div class="relative overflow-hidden rounded-xl bg-[#e3e5e4] flex items-stretch min-h-[175px] sm:min-h-[210px] lg:min-h-[225px]">
                                {{-- Left: Books Capsule Graphic --}}
                                <div class="w-5/12 sm:w-1/2 -ml-2 sm:-ml-3 shrink-0 flex items-center justify-center">
                                    <picture>
                                        <source srcset="{{ asset('logo/image-portal.avif') }}" type="image/avif">
                                        <source srcset="{{ asset('logo/image-portal.webp') }}" type="image/webp">
                                        <img src="{{ asset('logo/image-portal.png') }}"
                                             alt="{{ __('Portal Científico') }}"
                                             class="w-full h-auto max-h-[190px] sm:max-h-[220px] object-contain block drop-shadow-xs"
                                             loading="eager">
                                    </picture>
                                </div>

                                {{-- Right: Fully Translatable Text (PT / EN) --}}
                                <div class="w-[56%] flex flex-col justify-center py-4 pr-4 sm:py-6 sm:pr-8 pl-1 sm:pl-3 text-left">
                                    <h2 class="text-xl sm:text-2xl lg:text-[28px] font-bold text-[#286e36] tracking-tight leading-tight m-0 mb-2 sm:mb-3">
                                        {{ __('Portal Científico') }}
                                    </h2>
                                    <p class="text-xs sm:text-[13.5px] lg:text-[15px] font-normal text-black leading-relaxed sm:leading-relaxed m-0">
                                        {{ __('Um repositório centralizado que disponibiliza o património científico da Instituição aos seus utilizadores.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4 sm:mb-5 rounded-2xl border border-slate-200/80 bg-white shadow-sm overflow-hidden">
                        <div class="card-body p-6 sm:p-8">
                            <h3 class="mb-5 text-base sm:text-lg text-emerald-800 flex items-center gap-2">
                                <i class="fas fa-book-open text-emerald-600"></i>
                                <span>{{ __('Últimos artigos') }}</span>
                            </h3>

                            @if(!empty($lastOutputs) && count($lastOutputs))
                                @foreach ($lastOutputs as $publication)
                                    <div class="py-1.5">
                                        <x-publication-title :publication=$publication displayType="0"
                                            displayAccessPubButton="1" />
                                    </div>
                                    @if (!$loop->last)
                                        <hr class="my-4 border-slate-100">
                                    @endif
                                @endforeach
                            @else
                                <p class="text-sm text-slate-400 py-3">{{ __('Sem publicações recentes') }}</p>
                            @endif

                            @if (Auth::check())
                                <div class="mt-5 pt-2">
                                    <a href="{{ route('outputs.index') }}"
                                       class="inline-flex items-center text-xs sm:text-sm font-semibold uppercase tracking-wider text-slate-700 hover:text-emerald-800 transition-colors gap-2">
                                        <span>{{ __('Mostrar todas as Publicações') }}</span>
                                        <i class="fas fa-arrow-right text-xs"></i>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Coluna 3: Estatísticas e Links Úteis --}}
                <div class="col-xl-4">
                    {{-- Estatística Autores --}}
                    <div class="card mb-3 rounded-xl border border-slate-200/80 bg-white shadow-sm overflow-hidden">
                        <div class="p-3.5 sm:p-4">
                            <div class="flex items-center">
                                <div class="shrink-0">
                                    <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl flex items-center justify-center text-white text-base sm:text-lg shadow-xs bg-emerald-700">
                                        <i class="fas fa-user"></i>
                                    </div>
                                </div>
                                <div class="grow ms-3 sm:ms-3.5">
                                    <p class="text-[11px] sm:text-xs text-slate-500 mb-0.5 font-semibold uppercase tracking-wider">{{ __('Autores') }}</p>
                                    <div>
                                        <h2 class="mb-0 text-xl sm:text-2xl font-semibold text-slate-900 tracking-tight counter" id="number-of-authors"
                                            data-count="{{ $numberOfAuthors }}">
                                            {{ $numberOfAuthors }}
                                        </h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Estatística Artigos --}}
                    <div class="card mb-3 sm:mb-4 rounded-xl border border-slate-200/80 bg-white shadow-sm overflow-hidden">
                        <div class="p-3.5 sm:p-4">
                            <div class="flex items-center">
                                <div class="shrink-0">
                                    <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl flex items-center justify-center text-white text-base sm:text-lg shadow-xs bg-emerald-700">
                                        <i class="fas fa-newspaper"></i>
                                    </div>
                                </div>
                                <div class="grow ms-3 sm:ms-3.5">
                                    <p class="text-[11px] sm:text-xs text-slate-500 mb-0.5 font-semibold uppercase tracking-wider">{{ __('Artigos') }}</p>
                                    <div title="{{ __('Artigos') }}">
                                        <h2 class="mb-0 text-xl sm:text-2xl font-semibold text-slate-900 tracking-tight counter" id="number-of-outputs"
                                            data-count="{{ $numberOfOutputs }}">
                                            {{ $numberOfOutputs }}
                                        </h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Links Úteis --}}
                    <div class="card mb-4 sm:mb-5 rounded-2xl border border-slate-200/80 bg-white shadow-sm overflow-hidden">
                        <div class="card-body p-6 sm:p-7">
                            <h3 class="mb-4 text-base sm:text-lg text-emerald-800 flex items-center gap-2">
                                <i class="fas fa-link text-emerald-600"></i>
                                <span>{{ __('Links Úteis') }}</span>
                            </h3>
                            <div class="space-y-1">
                                @if(!empty($coursesFinal) && count($coursesFinal))
                                    @foreach ($coursesFinal as $course)
                                        @php
                                            $name = data_get($course, 'name', data_get($course, 'title.rendered', ''));
                                            $name = is_string($name) ? html_entity_decode($name) : '';
                                            $permalink = data_get($course, 'permalink', data_get($course, 'link', '#'));
                                        @endphp

                                        <a href="{{ $permalink }}"
                                            class="flex items-center py-2 px-2 rounded-lg text-xs sm:text-sm font-medium text-slate-700 hover:text-emerald-800 hover:bg-emerald-50/50 truncate transition-colors gap-2"
                                            target="_blank" rel="noopener noreferrer">
                                            <i class="fa-solid fa-chalkboard-user text-slate-400 shrink-0 text-sm"></i>
                                            <span class="truncate">{{ $name }}</span>
                                        </a>
                                    @endforeach
                                @else
                                    <div class="py-2 text-slate-400 text-xs sm:text-sm">{{ __('Sem cursos recentes') }}</div>
                                @endif

                                <a href="https://www.webofscience.com/wos/woscc/basic-search"
                                    class="flex items-center py-2 px-2 rounded-lg text-xs sm:text-sm font-medium text-slate-700 hover:text-emerald-800 hover:bg-emerald-50/50 truncate transition-colors gap-2"
                                    target="_blank" rel="noopener noreferrer">
                                    <i class="fa-solid fa-chalkboard-user text-slate-400 shrink-0 text-sm"></i>
                                    <span class="truncate">Web of Science</span>
                                </a>
                            </div>

                            <hr class="my-4 border-slate-100">

                            <a href="https://ci-islagaia.pt/formacao-online/" target="_blank" rel="noopener noreferrer"
                               class="inline-flex items-center text-xs sm:text-sm font-semibold uppercase tracking-wider text-slate-700 hover:text-emerald-800 transition-colors gap-2 pt-1">
                                <span>{{ __('Mostrar todos os links úteis') }}</span>
                                <i class="fas fa-arrow-right text-xs"></i>
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
