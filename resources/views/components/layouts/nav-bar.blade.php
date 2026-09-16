<!-- Start Header -->
<header class="header bg-white sticky top-0 z-50 transition-all shadow-xs" role="banner">
    {{-- Tier 1: Top Brand & User Bar --}}
    <div class="border-b border-slate-200/80 bg-white">
        <div class="w-full max-w-[1440px] mx-auto px-4 sm:px-6 py-3.5 sm:py-4 flex items-center justify-between gap-4">
            {{-- Brand Logo --}}
            <div class="logo flex items-center shrink-0">
                <a class="flex items-center hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-emerald-700/30 rounded-lg py-0.5 transition-opacity"
                   aria-label="Portal Científico - Página inicial" href="{{ route('home') }}" data-discover="true" wire:navigate.hover>
                    <img src="{{ asset('logo/logo-portal-cientifico.svg') }}" alt="Portal Científico"
                         class="h-[32px] sm:h-[36px] w-auto" width="165" height="32" fetchpriority="high">
                </a>
            </div>

            {{-- Right: User Area (Language + Login/Profile) --}}
            <div class="user-area flex items-center gap-2 sm:gap-2.5 shrink-0">
                {{-- Language Selector --}}
                <x-flags :flags="[
                    'en' => ['lang' => 'English'],
                    'pt' => ['lang' => 'Português'],
                ]" />

                {{-- Guest 'Entrar' Button OR Logged User Profile --}}
                @if (!Auth::check())
                    <button type="button"
                       onclick="if (typeof openAuthModal === 'function') { openAuthModal('login'); } else { window.location.href = '{{ route('login') }}'; }"
                       class="h-9 inline-flex items-center gap-2 px-3.5 sm:px-4 bg-emerald-700 hover:bg-emerald-800 active:bg-emerald-900 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-700/30 transition-all duration-150 shadow-xs hover:shadow font-semibold text-xs sm:text-sm cursor-pointer border border-emerald-700"
                       aria-label="{{ __('Entrar') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-in">
                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                            <polyline points="10 17 15 12 10 7"></polyline>
                            <line x1="15" x2="3" y1="12" y2="12"></line>
                        </svg>
                        <span>{{ __('Entrar') }}</span>
                    </button>
                @else
                    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                        <button type="button" @click="open = !open"
                                class="h-9 inline-flex items-center gap-2 px-2.5 text-slate-700 hover:text-emerald-800 bg-white hover:bg-slate-50 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-700/20 transition-all border border-slate-200 hover:border-slate-300 shadow-2xs cursor-pointer"
                                aria-label="{{ __('Menu de perfil') }}" :aria-expanded="open ? 'true' : 'false'">
                            <x-user-image showLogedUserImage="{{ auth()->user()->type != 'administrative' }}" height="26" width="26" class="rounded-full shrink-0 ring-1 ring-slate-200" />
                            <span class="text-xs sm:text-sm font-semibold text-slate-800 max-w-[130px] truncate">{{ auth()->user()->name }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': open }">
                                <path d="m6 9 6 6 6-6"></path>
                            </svg>
                        </button>

                        <ul class="dropdown-menu shadow-xl border border-slate-200/90 rounded-xl p-1 bg-white absolute right-0 mt-2 min-w-[190px] z-50 list-none"
                            x-show="open" x-cloak x-transition.opacity.duration.150ms>
                            @if (!auth()->user()->hasVerifiedEmail())
                                <li>
                                    <a class="dropdown-item px-3 py-2 text-xs font-semibold text-emerald-700 hover:bg-emerald-50 rounded-lg transition-colors flex items-center gap-2" href="javascript:void(0)" onclick="openVerifyEmailModal()">
                                        <i class="fas fa-envelope-open-text text-emerald-600"></i>{{ __('Verificar Email') }}
                                    </a>
                                </li>
                            @endif

                            <li>
                                <a class="dropdown-item px-3 py-2 text-xs font-medium text-slate-700 hover:text-emerald-800 hover:bg-emerald-50 rounded-lg transition-colors flex items-center"
                                    href="{{ route('profile.edit') }}" wire:navigate>{{ __('Perfil') }}</a>
                            </li>

                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item px-3 py-2 text-xs font-medium text-rose-600 hover:text-rose-800 hover:bg-rose-50 rounded-lg transition-colors w-full text-left flex items-center border-0 bg-transparent cursor-pointer">
                                        {{ __('Logout') }}
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Tier 2: Secondary Navigation Bar (Sub-header matching ISLA Biblioteca Digital) --}}
    <nav id="main-navigation" class="main-nav bg-white border-b border-slate-200/90 min-h-[46px]" role="navigation" aria-label="Navegação principal">
        <div class="w-full max-w-[1440px] mx-auto px-4 sm:px-6">
            {{-- Desktop Navigation (md and up) --}}
            <ul class="hidden md:flex items-center space-x-1 list-none p-0 m-0 min-h-[46px]">
                {{-- Início / Catálogo --}}
                <li>
                    <a href="{{ route('home') }}" wire:navigate.hover
                       class="nav-item flex items-center space-x-2 px-4 py-2.5 text-xs sm:text-sm font-semibold rounded-t-lg transition-colors duration-150 {{ request()->routeIs('home') ? 'bg-emerald-700 text-white shadow-xs' : 'text-slate-700 hover:text-emerald-800 hover:bg-slate-100/80' }}"
                       title="{{ __('Início') }}" aria-current="{{ request()->routeIs('home') ? 'page' : 'false' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-book-open shrink-0">
                            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                        </svg>
                        <span class="whitespace-nowrap">{{ __('Início') }}</span>
                    </a>
                </li>

                {{-- Sobre --}}
                <li>
                    <a href="{{ route('about.index') }}" wire:navigate.hover
                       class="nav-item flex items-center space-x-2 px-4 py-2.5 text-xs sm:text-sm font-semibold rounded-t-lg transition-colors duration-150 {{ request()->routeIs('about.*') ? 'bg-emerald-700 text-white shadow-xs' : 'text-slate-700 hover:text-emerald-800 hover:bg-slate-100/80' }}"
                       title="{{ __('Sobre') }}" aria-current="{{ request()->routeIs('about.*') ? 'page' : 'false' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-info shrink-0">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M12 16v-4"></path>
                            <path d="M12 8h.01"></path>
                        </svg>
                        <span class="whitespace-nowrap">{{ __('Sobre') }}</span>
                    </a>
                </li>

                @if (Auth::check())
                    {{-- Painel --}}
                    <li>
                        <a href="{{ auth()->user()->hasVerifiedEmail() ? route('dashboard') : 'javascript:void(0)' }}"
                           {{ auth()->user()->hasVerifiedEmail() ? 'wire:navigate' : '' }}
                           @if (!auth()->user()->hasVerifiedEmail()) onclick="openVerifyEmailModal()" @endif
                           class="nav-item flex items-center space-x-2 px-4 py-2.5 text-xs sm:text-sm font-semibold rounded-t-lg transition-colors duration-150 {{ request()->routeIs('dashboard') || (request()->routeIs('authors.*') && isset($author) && $author->user_id == auth()->id()) ? 'bg-emerald-700 text-white shadow-xs' : 'text-slate-700 hover:text-emerald-800 hover:bg-slate-100/80' }}"
                           title="{{ __('Painel') }}" aria-current="{{ request()->routeIs('dashboard') || (request()->routeIs('authors.*') && isset($author) && $author->user_id == auth()->id()) ? 'page' : 'false' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-layout-dashboard shrink-0">
                                <rect width="7" height="9" x="3" y="3" rx="1"></rect>
                                <rect width="7" height="5" x="14" y="3" rx="1"></rect>
                                <rect width="7" height="9" x="14" y="12" rx="1"></rect>
                                <rect width="7" height="5" x="3" y="16" rx="1"></rect>
                            </svg>
                            <span class="whitespace-nowrap">{{ __('Painel') }}</span>
                        </a>
                    </li>

                    {{-- Administrative: Utilizadores --}}
                    @if (auth()->user()->type == 'administrative')
                        <li class="relative" x-data="{ open: false }" @click.outside="open = false">
                            <button type="button" @click="open = !open"
                                    class="nav-item flex items-center space-x-2 px-4 py-2.5 text-xs sm:text-sm font-semibold rounded-t-lg transition-colors duration-150 cursor-pointer border-0 {{ request()->routeIs('user.*') ? 'bg-emerald-700 text-white shadow-xs' : 'bg-transparent text-slate-700 hover:text-emerald-800 hover:bg-slate-100/80' }}"
                                    aria-expanded="false" :aria-expanded="open ? 'true' : 'false'">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users shrink-0">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                                <span class="whitespace-nowrap">{{ __('Utilizadores') }}</span>
                                @if (($pendingApprovalCount ?? 0) > 0 || ($pendingVerificationCount ?? 0) > 0)
                                    <span class="inline-flex items-center justify-center px-1.5 py-0.2 text-[10px] font-bold text-white bg-rose-500 rounded-full shadow-2xs">
                                        {{ ($pendingApprovalCount ?? 0) + ($pendingVerificationCount ?? 0) }}
                                    </span>
                                @endif
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="transition-transform duration-200 shrink-0" :class="{ 'rotate-180': open }">
                                    <path d="m6 9 6 6 6-6"></path>
                                </svg>
                            </button>
                            <ul class="dropdown-menu shadow-xl border border-slate-200/90 rounded-xl p-1 bg-white absolute left-0 top-full mt-1 min-w-[200px] z-50 list-none"
                                x-show="open" x-cloak x-transition.opacity.duration.150ms>
                                <li>
                                    <a class="dropdown-item px-3 py-2 text-xs font-medium text-slate-700 hover:text-emerald-800 hover:bg-emerald-50 rounded-lg transition-colors flex items-center" href="{{ route('user.create') }}" wire:navigate>
                                        {{ __('Criar') }}
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item px-3 py-2 text-xs font-medium text-slate-700 hover:text-emerald-800 hover:bg-emerald-50 rounded-lg transition-colors flex items-center justify-between" href="{{ route('user.active') }}" wire:navigate>
                                        <span>{{ __('Utilizadores Ativos') }}</span>
                                        @if (($activeUserCount ?? 0) > 0)
                                            <span class="inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-bold text-white bg-emerald-600 rounded-full shadow-xs">{{ $activeUserCount }}</span>
                                        @endif
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item px-3 py-2 text-xs font-medium text-slate-700 hover:text-emerald-800 hover:bg-emerald-50 rounded-lg transition-colors flex items-center justify-between" href="{{ route('user.inactive') }}" wire:navigate>
                                        <span>{{ __('Utilizadores Inativos') }}</span>
                                        @if (($pendingApprovalCount ?? 0) > 0)
                                            <span class="inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-bold text-slate-900 bg-amber-400 rounded-full shadow-xs">{{ $pendingApprovalCount }}</span>
                                        @endif
                                    </a>
                                </li>
                            </ul>
                        </li>

                        {{-- Administrative: Qualidade Científica --}}
                        <li>
                            <a href="{{ route('admin') }}" wire:navigate
                               class="nav-item flex items-center space-x-2 px-4 py-2.5 text-xs sm:text-sm font-semibold rounded-t-lg transition-colors duration-150 {{ request()->routeIs('admin*') ? 'bg-emerald-700 text-white shadow-xs' : 'text-slate-700 hover:text-emerald-800 hover:bg-slate-100/80' }}"
                               title="{{ __('Qualidade Científica') }}" aria-current="{{ request()->routeIs('admin*') ? 'page' : 'false' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield-check shrink-0">
                                    <path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"></path>
                                    <path d="m9 12 2 2 4-4"></path>
                                </svg>
                                <span class="whitespace-nowrap">{{ __('Qualidade') }}</span>
                            </a>
                        </li>

                        {{-- Administrative: Estatísticas Globais --}}
                        <li>
                            <a href="{{ route('statistics') }}" wire:navigate
                               class="nav-item flex items-center space-x-2 px-4 py-2.5 text-xs sm:text-sm font-semibold rounded-t-lg transition-colors duration-150 {{ request()->routeIs('statistics') ? 'bg-emerald-700 text-white shadow-xs' : 'text-slate-700 hover:text-emerald-800 hover:bg-slate-100/80' }}"
                               title="{{ __('Estatísticas Globais') }}" aria-current="{{ request()->routeIs('statistics') ? 'page' : 'false' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bar-chart-3 shrink-0">
                                    <path d="M3 3v18h18"></path>
                                    <path d="M18 17V9"></path>
                                    <path d="M13 17V5"></path>
                                    <path d="M8 17v-3"></path>
                                </svg>
                                <span class="whitespace-nowrap">{{ __('Estatísticas') }}</span>
                            </a>
                        </li>
                    @else
                        @php
                            $reportEntities = auth()->user()->entities ?? [];
                            if (empty($reportEntities) && !empty(auth()->user()->entidade)) {
                                $reportEntities = [auth()->user()->entidade];
                            }
                            $reportEntities = array_values(array_unique(array_filter($reportEntities)));
                        @endphp
                        @if (count($reportEntities))
                            <li class="relative" x-data="{ open: false }" @click.outside="open = false">
                                <button type="button" @click="open = !open"
                                        class="nav-item flex items-center space-x-2 px-4 py-2.5 text-xs sm:text-sm font-semibold rounded-t-lg transition-colors duration-150 cursor-pointer border-0 bg-transparent text-slate-700 hover:text-emerald-800 hover:bg-slate-100/80"
                                        aria-expanded="false" :aria-expanded="open ? 'true' : 'false'">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-spreadsheet shrink-0">
                                        <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path>
                                        <path d="M14 2v4a2 2 0 0 0 2 2h4"></path>
                                        <path d="M8 13h2"></path>
                                        <path d="M14 13h2"></path>
                                        <path d="M8 17h2"></path>
                                        <path d="M14 17h2"></path>
                                    </svg>
                                    <span class="whitespace-nowrap">{{ __('Reporte') }}</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="transition-transform duration-200 shrink-0" :class="{ 'rotate-180': open }">
                                        <path d="m6 9 6 6 6-6"></path>
                                    </svg>
                                </button>
                                <ul class="dropdown-menu shadow-xl border border-slate-200/90 rounded-xl p-1 bg-white absolute left-0 top-full mt-1 min-w-[200px] z-50 list-none"
                                    x-show="open" x-cloak x-transition.opacity.duration.150ms>
                                    @foreach ($reportEntities as $entity)
                                        <li>
                                            <a class="dropdown-item report-link px-3 py-2 text-xs font-medium text-slate-700 hover:text-emerald-800 hover:bg-emerald-50 rounded-lg transition-colors flex items-center" href="#!"
                                               data-entity="{{ $entity }}" data-report-action="{{ route('ceos-excel') }}">
                                                {{ $entity }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endif
                    @endif

                    {{-- Investigação --}}
                    @if (Auth::user()->is_isla == 1)
                        @php
                            $isInvestigationActive = (request()->routeIs('authors.*') && (!isset($author) || $author->user_id != auth()->id())) || request()->routeIs('outputs.*');
                        @endphp
                        <li class="relative" x-data="{ open: false }" @click.outside="open = false">
                            <button type="button" @click="open = !open"
                                    class="nav-item flex items-center space-x-2 px-4 py-2.5 text-xs sm:text-sm font-semibold rounded-t-lg transition-colors duration-150 cursor-pointer border-0 {{ $isInvestigationActive ? 'bg-emerald-700 text-white shadow-xs' : 'bg-transparent text-slate-700 hover:text-emerald-800 hover:bg-slate-100/80' }}"
                                    aria-expanded="false" :aria-expanded="open ? 'true' : 'false'">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-microscope shrink-0">
                                    <path d="M6 18h8"></path>
                                    <path d="M3 22h18"></path>
                                    <path d="m14 22 3-3 3 3"></path>
                                    <path d="M9 14h2"></path>
                                    <path d="M9 12a2 2 0 0 1-2-2V6h6v4a2 2 0 0 1-2 2Z"></path>
                                    <path d="m12 6 4-4 3 3-4 4"></path>
                                </svg>
                                <span class="whitespace-nowrap">{{ __('Investigação') }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="transition-transform duration-200 shrink-0" :class="{ 'rotate-180': open }">
                                    <path d="m6 9 6 6 6-6"></path>
                                </svg>
                            </button>
                            <ul class="dropdown-menu shadow-xl border border-slate-200/90 rounded-xl p-1 bg-white absolute left-0 top-full mt-1 min-w-[200px] z-50 list-none"
                                x-show="open" x-cloak x-transition.opacity.duration.150ms>
                                <li>
                                    <a class="dropdown-item px-3 py-2 text-xs font-medium rounded-lg transition-colors flex items-center {{ request()->routeIs('authors.*') ? 'bg-emerald-50 text-emerald-800 font-semibold' : 'text-slate-700 hover:text-emerald-800 hover:bg-emerald-50' }}" href="{{ route('authors.index') }}" wire:navigate>
                                        {{ __('Autores') }}
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item px-3 py-2 text-xs font-medium rounded-lg transition-colors flex items-center {{ request()->routeIs('outputs.*') ? 'bg-emerald-50 text-emerald-800 font-semibold' : 'text-slate-700 hover:text-emerald-800 hover:bg-emerald-50' }}" href="{{ route('outputs.index') }}" wire:navigate>
                                        {{ __('Publicações') }}
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif
                @endif

                {{-- Ajuda / Tutorial --}}
                <li>
                    <a href="{{ route('help.index') }}" wire:navigate.hover
                       class="nav-item flex items-center space-x-2 px-4 py-2.5 text-xs sm:text-sm font-semibold rounded-t-lg transition-colors duration-150 {{ request()->routeIs('help.*') ? 'bg-emerald-700 text-white shadow-xs' : 'text-slate-700 hover:text-emerald-800 hover:bg-slate-100/80' }}"
                       title="{{ __('Ajuda') }}" aria-current="{{ request()->routeIs('help.*') ? 'page' : 'false' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-help-circle shrink-0">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                            <path d="M12 17h.01"></path>
                        </svg>
                        <span class="whitespace-nowrap">{{ __('Ajuda') }}</span>
                    </a>
                </li>
            </ul>

            {{-- Mobile Sub-Nav (Horizontal Scrollable Tabs matching ISLA design) --}}
            <div class="md:hidden relative">
                <ul class="flex items-center space-x-1.5 overflow-x-auto scrollbar-hide py-2 list-none m-0">
                    <li class="shrink-0">
                        <a href="{{ route('home') }}" wire:navigate.hover
                           class="flex items-center space-x-1.5 px-3 py-1.5 text-xs font-medium rounded-lg {{ request()->routeIs('home') ? 'bg-emerald-700 text-white shadow-xs' : 'text-slate-700 bg-slate-50 border border-slate-200' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                            </svg>
                            <span>{{ __('Início') }}</span>
                        </a>
                    </li>
                    <li class="shrink-0">
                        <a href="{{ route('about.index') }}" wire:navigate.hover
                           class="flex items-center space-x-1.5 px-3 py-1.5 text-xs font-medium rounded-lg {{ request()->routeIs('about.*') ? 'bg-emerald-700 text-white shadow-xs' : 'text-slate-700 bg-slate-50 border border-slate-200' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <path d="M12 16v-4"></path>
                                <path d="M12 8h.01"></path>
                            </svg>
                            <span>{{ __('Sobre') }}</span>
                        </a>
                    </li>
                    @if (Auth::check())
                        <li class="shrink-0">
                            <a href="{{ auth()->user()->hasVerifiedEmail() ? route('dashboard') : 'javascript:void(0)' }}"
                               {{ auth()->user()->hasVerifiedEmail() ? 'wire:navigate' : '' }}
                               @if (!auth()->user()->hasVerifiedEmail()) onclick="openVerifyEmailModal()" @endif
                               class="flex items-center space-x-1.5 px-3 py-1.5 text-xs font-medium rounded-lg {{ request()->routeIs('dashboard') || (request()->routeIs('authors.*') && isset($author) && $author->user_id == auth()->id()) ? 'bg-emerald-700 text-white shadow-xs' : 'text-slate-700 bg-slate-50 border border-slate-200' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect width="7" height="9" x="3" y="3" rx="1"></rect>
                                    <rect width="7" height="5" x="14" y="3" rx="1"></rect>
                                    <rect width="7" height="9" x="14" y="12" rx="1"></rect>
                                    <rect width="7" height="5" x="3" y="16" rx="1"></rect>
                                </svg>
                                <span>{{ __('Painel') }}</span>
                            </a>
                        </li>
                        @if (auth()->user()->type == 'administrative')
                            <li class="shrink-0">
                                <a href="{{ route('user.active') }}" wire:navigate
                                   class="flex items-center space-x-1.5 px-3 py-1.5 text-xs font-medium rounded-lg {{ request()->routeIs('user.*') ? 'bg-emerald-700 text-white shadow-xs' : 'text-slate-700 bg-slate-50 border border-slate-200' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="9" cy="7" r="4"></circle>
                                    </svg>
                                    <span>{{ __('Utilizadores') }}</span>
                                </a>
                            </li>
                            <li class="shrink-0">
                                <a href="{{ route('admin') }}" wire:navigate
                                   class="flex items-center space-x-1.5 px-3 py-1.5 text-xs font-medium rounded-lg {{ request()->routeIs('admin*') ? 'bg-emerald-700 text-white shadow-xs' : 'text-slate-700 bg-slate-50 border border-slate-200' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"></path>
                                        <path d="m9 12 2 2 4-4"></path>
                                    </svg>
                                    <span>{{ __('Qualidade') }}</span>
                                </a>
                            </li>
                            <li class="shrink-0">
                                <a href="{{ route('statistics') }}" wire:navigate
                                   class="flex items-center space-x-1.5 px-3 py-1.5 text-xs font-medium rounded-lg {{ request()->routeIs('statistics') ? 'bg-emerald-700 text-white shadow-xs' : 'text-slate-700 bg-slate-50 border border-slate-200' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M3 3v18h18"></path>
                                        <path d="M18 17V9"></path>
                                        <path d="M13 17V5"></path>
                                        <path d="M8 17v-3"></path>
                                    </svg>
                                    <span>{{ __('Estatísticas') }}</span>
                                </a>
                            </li>
                        @endif
                        @if (Auth::user()->is_isla == 1)
                            <li class="shrink-0">
                                <a href="{{ route('authors.index') }}" wire:navigate
                                   class="flex items-center space-x-1.5 px-3 py-1.5 text-xs font-medium rounded-lg {{ request()->routeIs('authors.*') ? 'bg-emerald-700 text-white shadow-xs' : 'text-slate-700 bg-slate-50 border border-slate-200' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M6 18h8"></path>
                                        <path d="M3 22h18"></path>
                                    </svg>
                                    <span>{{ __('Autores') }}</span>
                                </a>
                            </li>
                        @endif
                    @endif
                    <li class="shrink-0">
                        <a href="{{ route('help.index') }}" wire:navigate.hover
                           class="flex items-center space-x-1.5 px-3 py-1.5 text-xs font-medium rounded-lg {{ request()->routeIs('help.*') ? 'bg-emerald-700 text-white shadow-xs' : 'text-slate-700 bg-slate-50 border border-slate-200' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                                <path d="M12 17h.01"></path>
                            </svg>
                            <span>{{ __('Ajuda') }}</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
<!-- End Header -->