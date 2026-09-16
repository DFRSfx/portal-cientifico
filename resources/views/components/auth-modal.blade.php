@props([
    'defaultTab' => 'login',
    'isStandalone' => false
])

@php
    $errors = $errors ?? session('errors', new \Illuminate\Support\ViewErrorBag());
    $entitiesList = $entitiesList ?? \App\Models\Entity::orderByRaw("case when name = 'OTHER' then 1 else 0 end")->orderBy('name')->get();
    
    // Auto-select tab based on errors or session
    $initialTab = $defaultTab;
    if ($errors->has('ciencia_vitae')) {
        $initialTab = 'cienciaVitae';
    } elseif ($errors->has('name') || $errors->has('entities') || $errors->has('custom_entity') || ($errors->has('email') && old('name'))) {
        $initialTab = 'register';
    } elseif ($errors->has('email') && !old('username') && !old('name')) {
        $initialTab = 'recover';
    } elseif (session('status') && !old('username')) {
        $initialTab = 'recover';
    }

    $shouldOpen = $isStandalone || $errors->any() || session('status');
    $currentUrl = request()->fullUrl();
    $defaultRedirect = (!request()->routeIs('login') && !request()->routeIs('register') && !request()->routeIs('password.*')) ? $currentUrl : '';
@endphp

<div id="authModal"
     class="fixed inset-0 z-[1060] {{ $shouldOpen ? 'flex' : 'hidden' }} items-center justify-center p-3 sm:p-4 md:p-6 overflow-y-auto"
     role="dialog"
     aria-modal="true"
     aria-labelledby="authModalTitle">

    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-slate-950/70 backdrop-blur-xs transition-opacity"
         id="amBackdrop"
         onclick="closeAuthModal()"></div>

    {{-- Dialog Container --}}
    <div class="relative z-10 w-full max-w-[1020px] my-auto transition-all" role="document">
        <div class="relative grid grid-cols-1 lg:grid-cols-12 bg-white rounded-3xl shadow-2xl overflow-hidden border border-slate-200/80">

            {{-- Close / Exit button --}}
            <button type="button"
                    class="absolute top-4 right-4 z-20 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-400 hover:text-slate-700 flex items-center justify-center transition-all cursor-pointer focus:outline-none"
                    id="amCloseBtn"
                    onclick="closeAuthModal()"
                    aria-label="{{ __('Fechar') }}">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>

            {{-- ========================================================= --}}
            {{-- LEFT PANEL: Deep Green Hero & Navigation                  --}}
            {{-- ========================================================= --}}
            <div class="relative lg:col-span-5 bg-[#004d34] p-7 sm:p-9 lg:p-10 text-white flex flex-col justify-between overflow-hidden">
                <div class="space-y-6">
                    {{-- Frosted glass brand mark pill --}}
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 border border-white/15 text-xs font-semibold tracking-wider text-emerald-100">
                        <img src="{{ asset('logo/icon-portalcientifico.svg') }}" alt="Logo" class="w-4 h-4 object-contain" onerror="this.style.display='none'">
                        <span>{{ __('Scientific Portal') }}</span>
                    </div>

                    {{-- Headline & Subtitle --}}
                    <div class="space-y-3 pt-2">
                        <h2 id="authModalTitle" class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white leading-tight">
                            {{ __('Track outputs, projects, and reports in one place.') }}
                        </h2>
                        <p class="text-xs sm:text-sm text-emerald-100/80 leading-relaxed max-w-sm">
                            {{ __('Sign in to update your profile, manage outputs, and share results with your institution.') }}
                        </p>
                    </div>
                </div>

                {{-- Mode Switcher Navigation (Tabs) --}}
                <div class="mt-10 pt-6" role="tablist">
                    {{-- Subtle horizontal divider line --}}
                    <div class="border-t border-white/15 w-full mb-4"></div>

                    <div class="flex flex-col gap-2.5">
                        {{-- Tab 1: Iniciar Sessão --}}
                        <button type="button"
                                id="tabBtnLogin"
                                onclick="switchAuthTab('login')"
                                class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl text-xs font-extrabold tracking-wider uppercase transition-all cursor-pointer {{ in_array($initialTab, ['login', 'cienciaVitae']) ? 'bg-white text-slate-900 shadow-sm' : 'bg-transparent text-emerald-100/80 hover:text-white hover:bg-white/10' }}">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="shrink-0">
                                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                                <polyline points="10 17 15 12 10 7"></polyline>
                                <line x1="15" y1="12" x2="3" y2="12"></line>
                            </svg>
                            <span>{{ __('INICIAR SESSÃO') }}</span>
                        </button>

                        {{-- Tab 2: Registar --}}
                        <button type="button"
                                id="tabBtnRegister"
                                onclick="switchAuthTab('register')"
                                class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl text-xs font-extrabold tracking-wider uppercase transition-all cursor-pointer {{ $initialTab === 'register' ? 'bg-white text-slate-900 shadow-sm' : 'bg-transparent text-emerald-100/80 hover:text-white hover:bg-white/10' }}">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="shrink-0">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <line x1="19" y1="8" x2="19" y2="14"></line>
                                <line x1="22" y1="11" x2="16" y2="11"></line>
                            </svg>
                            <span>{{ __('REGISTAR') }}</span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- ========================================================= --}}
            {{-- RIGHT PANEL: Forms Panel                                  --}}
            {{-- ========================================================= --}}
            <div class="lg:col-span-7 p-7 sm:p-9 lg:p-10 flex flex-col justify-center bg-white">

                {{-- --------------------------------------------------------- --}}
                {{-- 1. LOGIN FORM                                             --}}
                {{-- --------------------------------------------------------- --}}
                <div id="amTabLogin" class="{{ $initialTab === 'login' ? 'block' : 'hidden' }} space-y-4">
                    <div class="space-y-1">
                        <h3 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900">
                            {{ __('Login') }}
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                            {{ __('Introduza as suas credenciais para aceder ao seu espaço de trabalho.') }}
                        </p>
                    </div>

                    @if ($errors->has('username') || ($errors->has('email') && !old('name')))
                        <div class="flex items-center gap-2.5 p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-medium" role="alert">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="shrink-0 text-rose-600">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg>
                            <span>{{ $errors->first('email') ?: $errors->first('username') }}</span>
                        </div>
                    @endif

                    @if (session('status'))
                        <div class="flex items-center gap-2.5 p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium" role="alert">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="shrink-0 text-emerald-600">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                            </svg>
                            <span>{{ session('status') }}</span>
                        </div>
                    @endif

                    {{-- Ciência Vitae In-Modal Trigger Button --}}
                    <div class="space-y-3 pt-1">
                        <button type="button"
                                onclick="switchAuthTab('cienciaVitae')"
                                id="amCienciaVitaeLoginBtn"
                                class="w-full flex items-center justify-between gap-3 py-2.5 px-3.5 rounded-2xl border border-emerald-200 bg-emerald-50/40 hover:bg-emerald-50 text-emerald-950 text-xs sm:text-sm font-bold transition-all group cursor-pointer shadow-2xs">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <span class="w-6 h-6 rounded-full bg-emerald-700 flex items-center justify-center shrink-0">
                                    <img src="{{ asset('logo/cienciaVitae.svg') }}"
                                         alt="Ciência Vitae"
                                         class="h-3.5 w-auto object-contain brightness-0 invert"
                                         onerror="this.src='{{ asset('logo/icon-portalcientifico.svg') }}'">
                                </span>
                                <span class="truncate font-bold text-slate-800 text-xs sm:text-sm">{{ __('Entrar com Ciência ID / Ciência Vitae') }}</span>
                            </div>
                            <span class="px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-900 text-[10px] font-extrabold uppercase tracking-wider shrink-0">
                                {{ __('SSO FCT') }}
                            </span>
                        </button>

                        {{-- Divider --}}
                        <div class="relative flex items-center justify-center">
                            <div class="w-full border-t border-slate-200"></div>
                            <span class="absolute bg-white px-3 text-[10px] sm:text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                                {{ __('OU COM CREDENCIAIS LOCAIS') }}
                            </span>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('login') }}" class="space-y-3.5" id="amLoginForm">
                        @csrf
                        <input type="hidden" name="redirect_to" value="{{ old('redirect_to', $defaultRedirect) }}">

                        <div class="space-y-1 text-left">
                            <label for="am_login_username" class="block text-xs font-semibold text-slate-700">
                                {{ __('Ciência Vitae ID ou Email') }} <span class="text-rose-500 font-bold">*</span>
                            </label>
                            <input type="text"
                                   name="username"
                                   id="am_login_username"
                                   class="w-full px-4 py-2.5 rounded-2xl border {{ $errors->has('username') ? 'border-rose-400 focus:ring-rose-500/20 focus:border-rose-600' : 'border-slate-300 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600' }} text-slate-900 placeholder:text-slate-400 text-xs sm:text-sm focus:outline-none transition-all bg-white"
                                   placeholder="Ex.: DXX3-X46X-XEX8 ou email@instituicao.pt"
                                   value="{{ old('username') }}"
                                   required
                                   autocomplete="username">
                        </div>

                        <div class="space-y-1 text-left">
                            <label for="am_login_password" class="block text-xs font-semibold text-slate-700">
                                {{ __('Palavra-passe') }} <span class="text-rose-500 font-bold">*</span>
                            </label>
                            <div class="relative">
                                <input type="password"
                                       name="password"
                                       id="am_login_password"
                                       class="w-full px-4 py-2.5 pr-11 rounded-2xl border {{ $errors->has('password') ? 'border-rose-400 focus:ring-rose-500/20 focus:border-rose-600' : 'border-slate-300 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600' }} text-slate-900 placeholder:text-slate-400 text-xs sm:text-sm focus:outline-none transition-all bg-white"
                                       placeholder="••••••••"
                                       required
                                       autocomplete="current-password">
                                <button type="button"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 p-1 text-slate-400 hover:text-slate-600 transition-colors focus:outline-none"
                                        onclick="togglePasswordVisibility('am_login_password', this)"
                                        aria-label="{{ __('Mostrar/Ocultar') }}">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="flex items-center justify-between gap-2 text-xs pt-1">
                            <label class="inline-flex items-center gap-2 cursor-pointer text-slate-600 hover:text-slate-900 select-none">
                                <input type="checkbox" name="remember" class="w-4 h-4 rounded-md border-slate-300 text-emerald-700 focus:ring-emerald-500 cursor-pointer" id="am_remember">
                                <span>{{ __('Lembrar-me') }}</span>
                            </label>

                            <button type="button"
                                    class="text-[#00684a] hover:underline font-bold transition-colors cursor-pointer"
                                    onclick="switchAuthTab('recover')">
                                {{ __('Esqueceu-se da palavra-passe?') }}
                            </button>
                        </div>

                        <button type="submit"
                                class="w-full mt-2 py-3 px-4 rounded-2xl bg-[#00684a] hover:bg-[#005238] text-white font-extrabold text-xs sm:text-sm tracking-wider uppercase shadow-sm transition-colors flex items-center justify-center gap-2 cursor-pointer disabled:opacity-60 disabled:cursor-not-allowed"
                                id="amLoginSubmitBtn">
                            <svg class="am-spinner hidden animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="am-btn-text">{{ __('INICIAR SESSÃO') }}</span>
                        </button>
                    </form>

                    <div class="pt-3 text-center text-xs text-slate-500 flex items-center justify-center gap-1.5">
                        <span>{{ __('Ainda não tem conta?') }}</span>
                        <button type="button" class="font-bold text-[#00684a] hover:underline transition-colors cursor-pointer" onclick="switchAuthTab('register')">
                            {{ __('Criar registo agora') }} &rarr;
                        </button>
                    </div>
                </div>

                {{-- --------------------------------------------------------- --}}
                {{-- 2. REGISTER FORM                                          --}}
                {{-- --------------------------------------------------------- --}}
                <div id="amTabRegister" class="{{ $initialTab === 'register' ? 'block' : 'hidden' }} space-y-4">
                    <div class="space-y-1">
                        <h3 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900">
                            {{ __('Registar') }}
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                            {{ __('Preencha os dados abaixo para registar o seu perfil científico.') }}
                        </p>
                    </div>

                    @if ($errors->any() && (old('name') || $errors->has('name') || $errors->has('entities')))
                        <div class="flex items-center gap-2.5 p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-medium" role="alert">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="shrink-0 text-rose-600">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg>
                            <span>{{ $errors->first() }}</span>
                        </div>
                    @endif

                    {{-- Ciência Vitae In-Modal Quick Trigger --}}
                    <div class="space-y-3 pt-1">
                        <button type="button"
                                onclick="switchAuthTab('cienciaVitae')"
                                id="amCienciaVitaeRegisterBtn"
                                class="w-full flex items-center justify-between gap-3 py-2.5 px-3.5 rounded-2xl border border-emerald-200 bg-emerald-50/40 hover:bg-emerald-50 text-emerald-950 text-xs sm:text-sm font-bold transition-all group cursor-pointer shadow-2xs">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <span class="w-6 h-6 rounded-full bg-emerald-700 flex items-center justify-center shrink-0">
                                    <img src="{{ asset('logo/cienciaVitae.svg') }}"
                                         alt="Ciência Vitae"
                                         class="h-3.5 w-auto object-contain brightness-0 invert"
                                         onerror="this.src='{{ asset('logo/icon-portalcientifico.svg') }}'">
                                </span>
                                <span class="truncate font-bold text-slate-800 text-xs sm:text-sm">{{ __('Registar automaticamente com Ciência ID') }}</span>
                            </div>
                            <span class="px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-900 text-[10px] font-extrabold uppercase tracking-wider shrink-0">
                                {{ __('Rápido') }}
                            </span>
                        </button>

                        {{-- Divider --}}
                        <div class="relative flex items-center justify-center">
                            <div class="w-full border-t border-slate-200"></div>
                            <span class="absolute bg-white px-3 text-[10px] sm:text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                                {{ __('OU PREENCHA O FORMULÁRIO MANUAL') }}
                            </span>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('register') }}" class="space-y-3" id="amRegisterForm">
                        @csrf
                        <input type="hidden" name="redirect_to" value="{{ old('redirect_to', $defaultRedirect) }}">
                        <input type="hidden" name="type" value="researcher">

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-left">
                            {{-- Entidade selection --}}
                            <div class="sm:col-span-2 space-y-1">
                                <label for="am_entities" class="block text-xs font-semibold text-slate-700">
                                    {{ __('Entidade Institucional') }} <span class="text-rose-500 font-bold">*</span>
                                </label>
                                <select id="am_entities"
                                        name="entities[]"
                                        class="w-full px-4 py-2.5 rounded-2xl border border-slate-300 text-slate-900 text-xs sm:text-sm focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 focus:outline-none transition-all bg-white cursor-pointer"
                                        required
                                        onchange="handleEntityChange(this)">
                                    <option value="" disabled {{ empty(old('entities')) ? 'selected' : '' }}>{{ __('Selecione a sua entidade') }}</option>
                                    @foreach ($entitiesList as $entity)
                                        <option value="{{ $entity->name }}" {{ in_array($entity->name, (array) old('entities', [])) ? 'selected' : '' }}>
                                            {{ $entity->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Custom entity input if other --}}
                            <div class="sm:col-span-2 space-y-1 {{ in_array('OTHER', (array) old('entities', [])) ? 'block' : 'hidden' }}"
                                 id="amCustomEntityWrap">
                                <label for="am_custom_entity" class="block text-xs font-semibold text-slate-700">
                                    {{ __('Designação da Outra Entidade') }} <span class="text-rose-500 font-bold">*</span>
                                </label>
                                <input type="text"
                                       id="am_custom_entity"
                                       name="custom_entity"
                                       class="w-full px-4 py-2.5 rounded-2xl border border-slate-300 text-slate-900 text-xs sm:text-sm focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 focus:outline-none transition-all bg-white"
                                       placeholder="Ex.: ISLA GAIA / CEOS.PP"
                                       value="{{ old('custom_entity') }}">
                            </div>

                            {{-- Name --}}
                            <div class="space-y-1">
                                <label for="am_reg_name" class="block text-xs font-semibold text-slate-700">
                                    {{ __('Nome Completo') }} <span class="text-rose-500 font-bold">*</span>
                                </label>
                                <input type="text"
                                       name="name"
                                       id="am_reg_name"
                                       class="w-full px-4 py-2.5 rounded-2xl border {{ $errors->has('name') ? 'border-rose-400 focus:border-rose-600' : 'border-slate-300 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600' }} text-slate-900 placeholder:text-slate-400 text-xs sm:text-sm focus:outline-none transition-all bg-white"
                                       placeholder="Ex.: Ana Silva"
                                       value="{{ old('name') }}"
                                       required
                                       autocomplete="name">
                            </div>

                            {{-- Email --}}
                            <div class="space-y-1">
                                <label for="am_reg_email" class="block text-xs font-semibold text-slate-700">
                                    {{ __('Email Institucional') }} <span class="text-rose-500 font-bold">*</span>
                                </label>
                                <input type="email"
                                       name="email"
                                       id="am_reg_email"
                                       class="w-full px-4 py-2.5 rounded-2xl border {{ $errors->has('email') ? 'border-rose-400 focus:border-rose-600' : 'border-slate-300 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600' }} text-slate-900 placeholder:text-slate-400 text-xs sm:text-sm focus:outline-none transition-all bg-white"
                                       placeholder="email@instituicao.pt"
                                       value="{{ old('email') }}"
                                       required
                                       autocomplete="email">
                            </div>

                            {{-- Ciencia Vitae ID --}}
                            <div class="sm:col-span-2 space-y-1">
                                <label for="am_reg_cv" class="block text-xs font-semibold text-slate-700">
                                    {{ __('Ciência Vitae ID') }}
                                </label>
                                <input type="text"
                                       name="ciencia_vitae"
                                       id="am_reg_cv"
                                       class="w-full px-4 py-2.5 rounded-2xl border {{ $errors->has('ciencia_vitae') ? 'border-rose-400 focus:border-rose-600' : 'border-slate-300 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600' }} text-slate-900 placeholder:text-slate-400 text-xs sm:text-sm focus:outline-none transition-all bg-white uppercase font-mono"
                                       placeholder="Ex.: DXX3-X46X-XEX8 (opcional)"
                                       value="{{ old('ciencia_vitae') }}">
                            </div>

                            {{-- Password --}}
                            <div class="space-y-1">
                                <label for="am_reg_password" class="block text-xs font-semibold text-slate-700">
                                    {{ __('Palavra-passe') }} <span class="text-rose-500 font-bold">*</span>
                                </label>
                                <div class="relative">
                                    <input type="password"
                                           name="password"
                                           id="am_reg_password"
                                           class="w-full px-4 py-2.5 pr-11 rounded-2xl border {{ $errors->has('password') ? 'border-rose-400 focus:border-rose-600' : 'border-slate-300 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600' }} text-slate-900 placeholder:text-slate-400 text-xs sm:text-sm focus:outline-none transition-all bg-white"
                                           placeholder="••••••••"
                                           required
                                           autocomplete="new-password">
                                    <button type="button"
                                            class="absolute right-3 top-1/2 -translate-y-1/2 p-1 text-slate-400 hover:text-slate-600 transition-colors focus:outline-none"
                                            onclick="togglePasswordVisibility('am_reg_password', this)"
                                            aria-label="{{ __('Mostrar/Ocultar') }}">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            {{-- Confirm Password --}}
                            <div class="space-y-1">
                                <label for="am_reg_pw_conf" class="block text-xs font-semibold text-slate-700">
                                    {{ __('Confirmar Palavra-passe') }} <span class="text-rose-500 font-bold">*</span>
                                </label>
                                <div class="relative">
                                    <input type="password"
                                           name="password_confirmation"
                                           id="am_reg_pw_conf"
                                           class="w-full px-4 py-2.5 pr-11 rounded-2xl border border-slate-300 text-slate-900 placeholder:text-slate-400 text-xs sm:text-sm focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 focus:outline-none transition-all bg-white"
                                           placeholder="••••••••"
                                           required
                                           autocomplete="new-password">
                                    <button type="button"
                                            class="absolute right-3 top-1/2 -translate-y-1/2 p-1 text-slate-400 hover:text-slate-600 transition-colors focus:outline-none"
                                            onclick="togglePasswordVisibility('am_reg_pw_conf', this)"
                                            aria-label="{{ __('Mostrar/Ocultar') }}">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <button type="submit"
                                class="w-full mt-2 py-3 px-4 rounded-2xl bg-[#00684a] hover:bg-[#005238] text-white font-extrabold text-xs sm:text-sm tracking-wider uppercase shadow-sm transition-colors flex items-center justify-center gap-2 cursor-pointer disabled:opacity-60 disabled:cursor-not-allowed"
                                id="amRegisterSubmitBtn">
                            <svg class="am-spinner hidden animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="am-btn-text">{{ __('CONCLUIR REGISTO') }}</span>
                        </button>
                    </form>

                    <div class="pt-3 text-center text-xs text-slate-500 flex items-center justify-center gap-1.5">
                        <span>{{ __('Já tem uma conta?') }}</span>
                        <button type="button" class="font-bold text-[#00684a] hover:underline transition-colors cursor-pointer" onclick="switchAuthTab('login')">
                            {{ __('Iniciar Sessão') }} &rarr;
                        </button>
                    </div>
                </div>

                {{-- --------------------------------------------------------- --}}
                {{-- 3. CIÊNCIA VITAE / CIÊNCIA ID AUTH TAB                    --}}
                {{-- --------------------------------------------------------- --}}
                <div id="amTabCienciaVitae" class="{{ $initialTab === 'cienciaVitae' ? 'block' : 'hidden' }} space-y-4">
                    <div class="space-y-1">
                        <div class="inline-flex items-center gap-2 px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-900 text-[10px] font-extrabold uppercase tracking-wider mb-1">
                            <i class="fas fa-shield-halved text-emerald-700"></i>
                            <span>{{ __('Identidade Digital FCT') }}</span>
                        </div>
                        <h3 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900">
                            {{ __('Ciência Vitae') }}
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                            {{ __('Inicie sessão ou crie a sua conta de investigador introduzindo o seu Ciência ID oficial.') }}
                        </p>
                    </div>

                    @if ($errors->has('ciencia_vitae'))
                        <div class="flex items-center gap-2.5 p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-medium" role="alert">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="shrink-0 text-rose-600">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg>
                            <span>{{ $errors->first('ciencia_vitae') }}</span>
                        </div>
                    @endif

                    {{-- In-modal Ciência Vitae Form --}}
                    <form method="POST" action="{{ route('auth.ciencia-vitae.callback') }}" class="space-y-4" id="amCienciaVitaeForm">
                        @csrf
                        <input type="hidden" name="redirect_to" value="{{ old('redirect_to', $defaultRedirect) }}">

                        <div class="space-y-1.5 text-left">
                            <label for="am_cv_ciencia_id" class="block text-xs font-semibold text-slate-700">
                                {{ __('Ciência ID ou Nome de Investigador') }} <span class="text-rose-500 font-bold">*</span>
                            </label>
                            <div class="relative">
                                <input type="text"
                                       name="ciencia_id"
                                       id="am_cv_ciencia_id"
                                       class="w-full px-4 py-3 rounded-2xl border border-slate-300 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 text-slate-900 placeholder:text-slate-400 text-xs sm:text-sm focus:outline-none transition-all bg-white"
                                       placeholder="Ex.: B61C-DDF6-1A69 ou o seu nome..."
                                       value="{{ old('ciencia_id') }}"
                                       required
                                       autocomplete="off">
                            </div>
                            <p class="text-[11px] text-slate-400 leading-relaxed">
                                {{ __('Pode introduzir o seu Ciência ID (ex.: B61C-DDF6-1A69) ou o seu nome.') }}
                            </p>
                        </div>

                        <button type="submit"
                                class="w-full mt-2 py-3 px-4 rounded-2xl bg-[#00684a] hover:bg-[#005238] text-white font-extrabold text-xs sm:text-sm tracking-wider uppercase shadow-sm transition-colors flex items-center justify-center gap-2 cursor-pointer disabled:opacity-60 disabled:cursor-not-allowed"
                                id="amCvSubmitBtn">
                            <svg class="am-spinner hidden animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="am-btn-text">{{ __('ENTRAR COM CIÊNCIA ID') }}</span>
                        </button>
                    </form>

                    {{-- Option to redirect to external OAuth server --}}
                    <div class="text-center pt-1">
                        <a href="{{ route('auth.ciencia-vitae.redirect') }}"
                           class="text-[11px] text-slate-400 hover:text-emerald-700 transition-colors">
                            {{ __('Ou autenticar via portal externo FCT (autenticacao.cienciavitae.pt)') }} &rarr;
                        </a>
                    </div>

                    {{-- Back to login --}}
                    <div class="pt-3 text-center text-xs">
                        <button type="button" class="font-bold text-[#00684a] hover:underline transition-colors cursor-pointer" onclick="switchAuthTab('login')">
                            &larr; {{ __('Voltar ao início de sessão com email') }}
                        </button>
                    </div>
                </div>

                {{-- --------------------------------------------------------- --}}
                {{-- 4. RECOVER PASSWORD FORM                                  --}}
                {{-- --------------------------------------------------------- --}}
                <div id="amTabRecover" class="{{ $initialTab === 'recover' ? 'block' : 'hidden' }} space-y-4">
                    <div class="space-y-1">
                        <h3 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900">
                            {{ __('Recuperar Palavra-passe') }}
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                            {{ __('Introduza o seu email institucional para receber um link de reposição.') }}
                        </p>
                    </div>

                    @if ($errors->has('email') && !old('name') && !old('username'))
                        <div class="flex items-center gap-2.5 p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-medium" role="alert">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="shrink-0 text-rose-600">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg>
                            <span>{{ $errors->first('email') }}</span>
                        </div>
                    @endif

                    @if (session('status'))
                        <div class="flex items-center gap-2.5 p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium" role="alert">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="shrink-0 text-emerald-600">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                            </svg>
                            <span>{{ session('status') }}</span>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}" class="space-y-3.5" id="amRecoverForm">
                        @csrf
                        <div class="space-y-1 text-left">
                            <label for="am_recover_email" class="block text-xs font-semibold text-slate-700">
                                {{ __('Email') }} <span class="text-rose-500 font-bold">*</span>
                            </label>
                            <input type="email"
                                   name="email"
                                   id="am_recover_email"
                                   class="w-full px-4 py-2.5 rounded-2xl border {{ $errors->has('email') ? 'border-rose-400 focus:border-rose-600' : 'border-slate-300 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600' }} text-slate-900 placeholder:text-slate-400 text-xs sm:text-sm focus:outline-none transition-all bg-white"
                                   placeholder="name@example.com"
                                   value="{{ old('email') }}"
                                   required
                                   autocomplete="email">
                        </div>

                        <button type="submit"
                                class="w-full mt-2 py-3 px-4 rounded-2xl bg-[#00684a] hover:bg-[#005238] text-white font-extrabold text-xs sm:text-sm tracking-wider uppercase shadow-sm transition-colors flex items-center justify-center gap-2 cursor-pointer disabled:opacity-60 disabled:cursor-not-allowed"
                                id="amRecoverSubmitBtn">
                            <svg class="am-spinner hidden animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="am-btn-text">{{ __('Enviar Link de Reposição') }}</span>
                        </button>
                    </form>

                    <div class="pt-3 text-center text-xs">
                        <button type="button" class="font-bold text-[#00684a] hover:underline transition-colors cursor-pointer" onclick="switchAuthTab('login')">
                            &larr; {{ __('Voltar ao início de sessão') }}
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Native JavaScript Interactions --}}
    <script>
        function switchAuthTab(tabName) {
            const tabs = ['login', 'register', 'recover', 'cienciaVitae'];
            tabs.forEach(t => {
                const el = document.getElementById('amTab' + t.charAt(0).toUpperCase() + t.slice(1));
                if (el) {
                    el.classList.add('hidden');
                    el.classList.remove('block');
                }
            });

            // Normalise tab name if passed with dashes
            if (tabName === 'ciencia-vitae') tabName = 'cienciaVitae';

            const activeContent = document.getElementById('amTab' + tabName.charAt(0).toUpperCase() + tabName.slice(1));
            if (activeContent) {
                activeContent.classList.remove('hidden');
                activeContent.classList.add('block');
            }

            // Update left nav button highlights
            const loginBtn = document.getElementById('tabBtnLogin');
            const regBtn = document.getElementById('tabBtnRegister');
            
            const activeClasses = ['bg-white', 'text-slate-900', 'shadow-sm'];
            const inactiveClasses = ['bg-transparent', 'text-emerald-100/80', 'hover:text-white', 'hover:bg-white/10'];

            if (loginBtn && regBtn) {
                if (tabName === 'login' || tabName === 'cienciaVitae') {
                    loginBtn.classList.add(...activeClasses);
                    loginBtn.classList.remove(...inactiveClasses);
                    regBtn.classList.remove(...activeClasses);
                    regBtn.classList.add(...inactiveClasses);
                } else if (tabName === 'register') {
                    regBtn.classList.add(...activeClasses);
                    regBtn.classList.remove(...inactiveClasses);
                    loginBtn.classList.remove(...activeClasses);
                    loginBtn.classList.add(...inactiveClasses);
                } else {
                    loginBtn.classList.remove(...activeClasses);
                    loginBtn.classList.add(...inactiveClasses);
                    regBtn.classList.remove(...activeClasses);
                    regBtn.classList.add(...inactiveClasses);
                }
            }

            // Focus on primary input of active tab
            setTimeout(() => {
                if (tabName === 'login') document.getElementById('am_login_username')?.focus();
                else if (tabName === 'cienciaVitae') document.getElementById('am_cv_ciencia_id')?.focus();
                else if (tabName === 'register') document.getElementById('am_entities')?.focus();
                else if (tabName === 'recover') document.getElementById('am_recover_email')?.focus();
            }, 80);
        }

        function selectCienciaVitaeResearcher(cvId) {
            const input = document.getElementById('am_cv_ciencia_id');
            if (input) {
                input.value = cvId;
                const form = document.getElementById('amCienciaVitaeForm');
                if (form) {
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        const spinner = submitBtn.querySelector('.am-spinner');
                        const text = submitBtn.querySelector('.am-btn-text');
                        if (spinner) spinner.classList.remove('hidden');
                        if (text) text.textContent = '{{ __("A autenticar...") }}';
                    }
                    form.submit();
                }
            }
        }

        function togglePasswordVisibility(inputId, btn) {
            const input = document.getElementById(inputId);
            if (!input) return;
            const icon = btn.querySelector('svg');
            if (input.type === 'password') {
                input.type = 'text';
                if (icon) {
                    icon.innerHTML = `
                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                        <line x1="1" y1="1" x2="23" y2="23"></line>
                    `;
                }
            } else {
                input.type = 'password';
                if (icon) {
                    icon.innerHTML = `
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    `;
                }
            }
        }

        function handleEntityChange(selectEl) {
            const customWrap = document.getElementById('amCustomEntityWrap');
            if (!customWrap) return;
            if (selectEl.value === 'OTHER') {
                customWrap.classList.remove('hidden');
                customWrap.classList.add('block');
                document.getElementById('am_custom_entity')?.focus();
            } else {
                customWrap.classList.add('hidden');
                customWrap.classList.remove('block');
            }
        }

        window.openAuthModal = function (tab = 'login') {
            const modal = document.getElementById('authModal');
            if (!modal) return;

            // Dynamically update redirect_to to current page URL if not on login/register route
            const currentPath = window.location.pathname;
            if (!currentPath.includes('/login') && !currentPath.includes('/register') && !currentPath.includes('/forgot-password')) {
                const redirectInputs = modal.querySelectorAll('input[name="redirect_to"]');
                redirectInputs.forEach(input => {
                    input.value = window.location.href;
                });
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            if (typeof window.switchAuthTab === 'function') {
                window.switchAuthTab(tab);
            }
            document.body.style.overflow = 'hidden';
        };

        window.closeAuthModal = function () {
            const modal = document.getElementById('authModal');
            if (!modal) return;

            @if ($isStandalone)
                if (window.history.length > 1 && document.referrer && !document.referrer.includes('/login') && !document.referrer.includes('/register')) {
                    window.history.back();
                } else {
                    window.location.href = "{{ url('/') }}";
                }
            @else
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = '';
            @endif
        };

        // Delegated Escape key listener
        if (!window._amKeydownBound) {
            window._amKeydownBound = true;
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    const modal = document.getElementById('authModal');
                    if (modal && !modal.classList.contains('hidden')) {
                        window.closeAuthModal();
                    }
                }
            });
        }

        // Delegated form submission loading states
        if (!window._amSubmitBound) {
            window._amSubmitBound = true;
            document.addEventListener('submit', function (e) {
                const f = e.target;
                if (f && (f.id === 'amLoginForm' || f.id === 'amRegisterForm' || f.id === 'amRecoverForm' || f.id === 'amCienciaVitaeForm')) {
                    const btn = f.querySelector('button[type="submit"]');
                    if (btn) {
                        btn.disabled = true;
                        const spinner = btn.querySelector('.am-spinner');
                        const text = btn.querySelector('.am-btn-text');
                        if (spinner) {
                            spinner.classList.remove('hidden');
                            spinner.classList.add('inline-block');
                        }
                        if (text) text.textContent = '{{ __("A processar...") }}';
                    }
                }
            });
        }
    </script>
</div>
