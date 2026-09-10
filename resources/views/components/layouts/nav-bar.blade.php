<!--Start header-->
<header class="sticky top-0 z-50">
    <!-- Start Navbar with Alpine.js -->
    <nav class="w-full bg-white/95 backdrop-blur-md border-b border-slate-200/80 shadow-xs transition-all" x-data="{ mobileOpen: false }">
        <div class="container flex flex-wrap items-center justify-between py-2">
            <a class="navbar-brand py-1 hover:opacity-90 transition-opacity flex items-center" href="/">
                <img src="{{ asset('logo/logo-portal-cientifico.svg') }}"
                    alt="Logotipo Global Alumni Network" width="160px" height="auto">
            </a>
            <!-- Mobile Toggle button -->
            <button class="p-2 text-slate-700 hover:text-emerald-700 lg:hidden border-0 bg-transparent focus:outline-none cursor-pointer rounded-lg transition-colors" type="button"
                @click="mobileOpen = !mobileOpen" aria-label="Toggle navigation">
                <i class="fas fa-bars fa-lg"></i>
            </button>

            <!-- Collapsible menu -->
            <div class="w-full lg:w-auto lg:flex items-center gap-1 mt-3 lg:mt-0 transition-all"
                :class="{ 'hidden': !mobileOpen, 'block': mobileOpen }">
                <!-- Menu links -->
                <ul class="flex flex-col lg:flex-row items-start lg:items-center gap-1 list-none p-0 m-0 w-full lg:w-auto">

                    <li class="nav-item px-1 w-full lg:w-auto">
                        <a class="nav-link block font-medium text-slate-700 hover:text-emerald-700 transition-colors py-2 px-3 rounded-lg hover:bg-slate-50 lg:hover:bg-transparent" aria-current="pagestatistics"
                            href="{{ route('about.index') }}">{{ __('Sobre') }}</a>
                    </li>

                    @if (Auth::check())
                        <li class="nav-item px-1 w-full lg:w-auto">
                            @if (!auth()->user()->hasVerifiedEmail())
                                <a class="nav-link block font-medium text-slate-700 hover:text-emerald-700 transition-colors py-2 px-3 rounded-lg hover:bg-slate-50 lg:hover:bg-transparent" href="javascript:void(0)" onclick="openVerifyEmailModal()" title="{{ __('Verificação de email necessária') }}">{{ __('Painel') }}</a>
                            @else
                                <a class="nav-link block font-medium text-slate-700 hover:text-emerald-700 transition-colors py-2 px-3 rounded-lg hover:bg-slate-50 lg:hover:bg-transparent" aria-current="pagestatistics"
                                    href="{{ route('dashboard') }}">{{ __('Painel') }}</a>
                            @endif
                        </li>

                        @if (auth()->user()->type == 'administrative')
                            <li class="relative nav-item px-1 w-full lg:w-auto" x-data="{ open: false }">
                                <a class="nav-link font-medium text-slate-700 hover:text-emerald-700 transition-colors flex items-center justify-between lg:justify-start gap-1.5 py-2 px-3 rounded-lg hover:bg-slate-50 lg:hover:bg-transparent cursor-pointer w-full"
                                    href="#" @click.prevent="open = !open">
                                    <span class="flex items-center">
                                        {{ __('Utilizadores') }}
                                        @if (($pendingApprovalCount ?? 0) > 0 || ($pendingVerificationCount ?? 0) > 0)
                                            <span class="ms-1.5 inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-bold text-white bg-rose-500 rounded-full shadow-xs">
                                                {{ ($pendingApprovalCount ?? 0) + ($pendingVerificationCount ?? 0) }}
                                            </span>
                                        @endif
                                    </span>
                                    <i class="fas fa-chevron-down text-[9px] text-slate-400 transition-transform duration-150" :class="{ 'rotate-180': open }"></i>
                                </a>
                                <ul class="dropdown-menu shadow-lg border border-slate-200/80 rounded-xl py-1.5 bg-white/98 backdrop-blur-md static lg:absolute lg:right-0 mt-1 min-w-[200px] z-50 list-none"
                                    x-show="open" @click.outside="open = false" x-transition.opacity.duration.150ms style="display: none;">
                                    <li>
                                        <a class="dropdown-item px-3 py-2 text-xs font-medium text-slate-700 hover:text-emerald-800 hover:bg-emerald-50 rounded-lg mx-1 transition-colors flex items-center" aria-current="pageAuthors"
                                            href="{{ route('user.create') }}">{{ __('Criar') }}</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item px-3 py-2 text-xs font-medium text-slate-700 hover:text-emerald-800 hover:bg-emerald-50 rounded-lg mx-1 transition-colors flex items-center justify-between" aria-current="pageAuthors"
                                            href="{{ route('user.active') }}">{{ __('Utilizadores Ativos') }}
                                            @if (($activeUserCount ?? 0) > 0)
                                                <span class="ms-1.5 inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-bold text-white bg-emerald-600 rounded-full shadow-xs">{{ $activeUserCount }}</span>
                                            @endif
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item px-3 py-2 text-xs font-medium text-slate-700 hover:text-emerald-800 hover:bg-emerald-50 rounded-lg mx-1 transition-colors flex items-center justify-between" aria-current="pageAuthors"
                                            href="{{ route('user.inactive') }}">{{ __('Utilizadores Inativos') }}
                                            @if (($pendingApprovalCount ?? 0) > 0)
                                                <span class="ms-1.5 inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-bold text-slate-900 bg-amber-400 rounded-full shadow-xs">{{ $pendingApprovalCount }}</span>
                                            @endif
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @else
                            <li class="relative nav-item px-1 w-full lg:w-auto" x-data="{ open: false }">
                                <a class="nav-link font-medium text-slate-700 hover:text-emerald-700 transition-colors flex items-center justify-between lg:justify-start gap-1.5 py-2 px-3 rounded-lg hover:bg-slate-50 lg:hover:bg-transparent cursor-pointer w-full"
                                    href="#" @click.prevent="open = !open">
                                    <span>{{ __('Reporte') }}</span>
                                    <i class="fas fa-chevron-down text-[9px] text-slate-400 transition-transform duration-150" :class="{ 'rotate-180': open }"></i>
                                </a>
                                <ul class="dropdown-menu shadow-lg border border-slate-200/80 rounded-xl py-1.5 bg-white/98 backdrop-blur-md static lg:absolute lg:right-0 mt-1 min-w-[200px] z-50 list-none"
                                    x-show="open" @click.outside="open = false" x-transition.opacity.duration.150ms style="display: none;">
                                    @php
                                        $reportEntities = auth()->user()->entities ?? [];
                                        if (empty($reportEntities) && !empty(auth()->user()->entidade)) {
                                            $reportEntities = [auth()->user()->entidade];
                                        }
                                        $reportEntities = array_values(array_unique(array_filter($reportEntities)));
                                    @endphp

                                    @foreach ($reportEntities as $entity)
                                        <li>
                                            <a class="dropdown-item report-link px-3 py-2 text-xs font-medium text-slate-700 hover:text-emerald-800 hover:bg-emerald-50 rounded-lg mx-1 transition-colors flex items-center" aria-current="pageAuthors" href="#!"
                                                data-entity="{{ $entity }}"
                                                data-report-action="{{ route('ceos-excel') }}">
                                                {{ $entity }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endif

                        @if (Auth::user()->is_isla == 1)
                            <li class="relative nav-item px-1 w-full lg:w-auto" x-data="{ open: false }">
                                <a class="nav-link font-medium text-slate-700 hover:text-emerald-700 transition-colors flex items-center justify-between lg:justify-start gap-1.5 py-2 px-3 rounded-lg hover:bg-slate-50 lg:hover:bg-transparent cursor-pointer w-full"
                                    href="#" @click.prevent="open = !open">
                                    <span>{{ __('Investigação') }}</span>
                                    <i class="fas fa-chevron-down text-[9px] text-slate-400 transition-transform duration-150" :class="{ 'rotate-180': open }"></i>
                                </a>
                                <ul class="dropdown-menu shadow-lg border border-slate-200/80 rounded-xl py-1.5 bg-white/98 backdrop-blur-md static lg:absolute lg:right-0 mt-1 min-w-[200px] z-50 list-none"
                                    x-show="open" @click.outside="open = false" x-transition.opacity.duration.150ms style="display: none;">
                                    <li>
                                        <a class="dropdown-item px-3 py-2 text-xs font-medium text-slate-700 hover:text-emerald-800 hover:bg-emerald-50 rounded-lg mx-1 transition-colors flex items-center" aria-current="pageAuthors"
                                            href="{{ route('authors.index') }}">{{ __('Autores') }}</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item px-3 py-2 text-xs font-medium text-slate-700 hover:text-emerald-800 hover:bg-emerald-50 rounded-lg mx-1 transition-colors flex items-center"
                                            href="{{ route('outputs.index') }}">{{ __('Publicações') }}</a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    @endif

                    {{-- Displays the flags --}}
                    <x-flags :flags="[
                        'en' => [
                            'lang' => 'Inglês',
                        ],
                        'pt' => [
                            'lang' => 'Português',
                        ],
                    ]" />

                    <li class="relative nav-item px-1 w-full lg:w-auto" x-data="{ open: false }">
                        <a class="nav-link py-1 cursor-pointer flex items-center gap-1.5" href="#"
                            @click.prevent="open = !open">
                            <x-user-image
                                showLogedUserImage="{{ Auth::check() && auth()->user()->type != 'administrative' }}"
                                height="34" />
                            <i class="fas fa-caret-down text-slate-500 text-xs transition-transform duration-150" :class="{ 'rotate-180': open }"></i>
                        </a>

                        <ul class="dropdown-menu shadow-lg border border-slate-200/80 rounded-xl py-1.5 bg-white/98 backdrop-blur-md absolute right-0 mt-1 min-w-[180px] z-50 list-none"
                            x-show="open" @click.outside="open = false" x-transition.opacity.duration.150ms style="display: none;">
                            @if (!Auth::check())
                                <li>
                                    <a class="dropdown-item px-3 py-2 text-xs font-semibold text-slate-700 hover:text-emerald-800 hover:bg-emerald-50 rounded-lg mx-1 transition-colors flex items-center"
                                        href="{{ route('login') }}">{{ __('Iniciar Sessão') }}</a>
                                </li>
                            @else
                                @if (!auth()->user()->hasVerifiedEmail())
                                    <li>
                                        <a class="dropdown-item px-3 py-2 text-xs font-semibold text-emerald-700 hover:bg-emerald-50 rounded-lg mx-1 transition-colors flex items-center gap-2" href="javascript:void(0)" onclick="openVerifyEmailModal()">
                                            <i class="fas fa-envelope-open-text text-emerald-600"></i>{{ __('Verificar Email') }}
                                        </a>
                                    </li>
                                @endif

                                <li>
                                    <a class="dropdown-item px-3 py-2 text-xs font-medium text-slate-700 hover:text-emerald-800 hover:bg-emerald-50 rounded-lg mx-1 transition-colors flex items-center"
                                        href="{{ route('profile.edit') }}">{{ __('Perfil') }}</a>
                                </li>

                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item px-3 py-2 text-xs font-medium text-rose-600 hover:text-rose-800 hover:bg-rose-50 rounded-lg mx-1 transition-colors w-full text-left flex items-center border-0 bg-transparent cursor-pointer">
                                            {{ __('Logout') }}
                                        </button>
                                    </form>
                                </li>
                            @endif
                        </ul>
                    </li>

                </ul>
                <!-- Menu links -->
            </div>
            <!-- Collapsible wrapper -->
        </div>
        <!-- Container wrapper -->
    </nav>
    <!-- End Navbar -->
</header>
<!-- End header-->
