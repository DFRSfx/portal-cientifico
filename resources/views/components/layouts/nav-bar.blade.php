<!--Start header-->
<header class="sticky top-0 z-50">
    <!-- Start Navbar -->
    <nav class="navbar navbar-expand-lg bg-white/90 backdrop-blur-md border-b border-slate-200/80 shadow-xs transition-all">
        <div class="container">
            <a class="navbar-brand img-fluid py-1 hover:opacity-90 transition-opacity" href="/">
                <img src="{{ asset('logo/logo-portal-cientifico.svg') }}"
                    alt="Logotipo Global Alumni Network" width="160px" height="auto">
            </a>
            <!-- Toggle button -->
            <button class="navbar-toggler ms-auto border-0 text-slate-700 shadow-none focus:outline-none" type="button" data-mdb-toggle="collapse"
                data-mdb-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Collapsible wrapper -->
            <div class="collapse navbar-collapse col-lg-10" id="navbarSupportedContent">
                <!-- Menu links -->
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center gap-1">

                    <li class="nav-item px-1">
                        <a class="nav-link font-medium text-slate-700 hover:text-emerald-700 transition-colors" aria-current="pagestatistics"
                            href="{{ route('about.index') }}">{{ __('Sobre') }}</a>
                    </li>

                    @if (Auth::check())
                        <li class="nav-item dropdown px-1">
                            @if (!auth()->user()->hasVerifiedEmail())
                                <a class="nav-link font-medium text-slate-700 hover:text-emerald-700 transition-colors" href="javascript:void(0)" onclick="openVerifyEmailModal()" title="{{ __('Verificação de email necessária') }}">{{ __('Painel') }}</a>
                            @else
                                <a class="nav-link font-medium text-slate-700 hover:text-emerald-700 transition-colors" aria-current="pagestatistics"
                                    href="{{ route('dashboard') }}">{{ __('Painel') }}</a>
                            @endif
                        </li>

                        @if (auth()->user()->type == 'administrative')
                            <li class="nav-item dropdown px-1">
                                <a class="nav-link dropdown-toggle font-medium text-slate-700 hover:text-emerald-700 transition-colors" href="#"
                                    id="navbarDropdownMenuLink" role="button" data-mdb-toggle="dropdown"
                                    aria-expanded="false">
                                    {{ __('Utilizadores') }}
                                    @if (($pendingApprovalCount ?? 0) > 0 || ($pendingVerificationCount ?? 0) > 0)
                                        <span class="ms-1.5 inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-bold text-white bg-rose-500 rounded-full shadow-xs">
                                            {{ ($pendingApprovalCount ?? 0) + ($pendingVerificationCount ?? 0) }}
                                        </span>
                                    @endif
                                </a>
                                <ul class="dropdown-menu shadow-lg border border-slate-200/80 rounded-xl py-1.5 bg-white/95 backdrop-blur-md" aria-labelledby="navbarDropdownMenuInvestigação">
                                    <li>
                                        <a class="dropdown-item px-3 py-2 text-xs font-medium text-slate-700 hover:text-emerald-800 hover:bg-emerald-50 rounded-lg mx-1 transition-colors" aria-current="pageAuthors"
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
                            <li class="nav-item dropdown px-1">
                                <a class="nav-link dropdown-toggle font-medium text-slate-700 hover:text-emerald-700 transition-colors" href="#"
                                    id="navbarDropdownMenuLink" role="button" data-mdb-toggle="dropdown"
                                    aria-expanded="false">
                                    {{ __('Reporte') }}
                                </a>
                                <ul class="dropdown-menu shadow-lg border border-slate-200/80 rounded-xl py-1.5 bg-white/95 backdrop-blur-md" aria-labelledby="navbarDropdownMenuInvestigação">
                                    @php
                                        $reportEntities = auth()->user()->entities ?? [];
                                        if (empty($reportEntities) && !empty(auth()->user()->entidade)) {
                                            $reportEntities = [auth()->user()->entidade];
                                        }
                                        $reportEntities = array_values(array_unique(array_filter($reportEntities)));
                                    @endphp

                                    @foreach ($reportEntities as $entity)
                                        <li>
                                            <a class="dropdown-item report-link px-3 py-2 text-xs font-medium text-slate-700 hover:text-emerald-800 hover:bg-emerald-50 rounded-lg mx-1 transition-colors" aria-current="pageAuthors" href="#!"
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
                            <li class="nav-item dropdown px-1">
                                <a class="nav-link dropdown-toggle font-medium text-slate-700 hover:text-emerald-700 transition-colors" href="#"
                                    id="navbarDropdownMenuLink" role="button" data-mdb-toggle="dropdown"
                                    aria-expanded="false">
                                    {{ __('Investigação') }}
                                </a>
                                <ul class="dropdown-menu shadow-lg border border-slate-200/80 rounded-xl py-1.5 bg-white/95 backdrop-blur-md" aria-labelledby="navbarDropdownMenuInvestigação">
                                    <li>
                                        <a class="dropdown-item px-3 py-2 text-xs font-medium text-slate-700 hover:text-emerald-800 hover:bg-emerald-50 rounded-lg mx-1 transition-colors" aria-current="pageAuthors"
                                            href="{{ route('authors.index') }}">{{ __('Autores') }}</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item px-3 py-2 text-xs font-medium text-slate-700 hover:text-emerald-800 hover:bg-emerald-50 rounded-lg mx-1 transition-colors"
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

                    <li class="nav-item dropdown px-1">
                        <a class="nav-link dropdown-toggle py-1" href="/" id="navbarDropdownMenuLink"
                            role="button" data-mdb-toggle="dropdown" aria-expanded="false">
                            <x-user-image
                                showLogedUserImage="{{ Auth::check() && auth()->user()->type != 'administrative' }}" />
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border border-slate-200/80 rounded-xl py-1.5 bg-white/95 backdrop-blur-md min-w-[180px]" aria-labelledby="navbarDropdownMenuUserLogin">
                            @if (!Auth::check())
                                <li>
                                    <a class="dropdown-item px-3 py-2 text-xs font-semibold text-slate-700 hover:text-emerald-800 hover:bg-emerald-50 rounded-lg mx-1 transition-colors"
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
                                    <a class="dropdown-item px-3 py-2 text-xs font-medium text-slate-700 hover:text-emerald-800 hover:bg-emerald-50 rounded-lg mx-1 transition-colors"
                                        href="{{ route('profile.edit') }}">{{ __('Perfil') }}</a>
                                </li>

                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item px-3 py-2 text-xs font-medium text-rose-600 hover:text-rose-800 hover:bg-rose-50 rounded-lg mx-1 transition-colors w-full text-left">
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
