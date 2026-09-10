@props([
    'defaultTab' => 'login',
    'isStandalone' => false
])

@php
    $errors = $errors ?? session('errors', new \Illuminate\Support\ViewErrorBag());
    $entitiesList = $entitiesList ?? \App\Models\Entity::orderByRaw("case when name = 'OTHER' then 1 else 0 end")->orderBy('name')->get();
    
    // Auto-select tab based on errors or session
    $initialTab = $defaultTab;
    if ($errors->has('name') || $errors->has('entities') || $errors->has('custom_entity') || ($errors->has('email') && old('name'))) {
        $initialTab = 'register';
    } elseif ($errors->has('email') && !old('username') && !old('name')) {
        $initialTab = 'recover';
    } elseif (session('status') && !old('username')) {
        $initialTab = 'recover';
    }
@endphp

<div id="authModal" class="am-overlay" role="dialog" aria-modal="true" aria-labelledby="authModalTitle" style="{{ $isStandalone ? 'display: flex;' : 'display: none;' }}">
    <div class="am-backdrop" id="amBackdrop"></div>

    <div class="am-dialog" role="document">
        <div class="am-container">
            <!-- Close / Exit button -->
            <a href="{{ $isStandalone ? url('/') : 'javascript:void(0)' }}" class="am-close-btn" id="amCloseBtn" aria-label="{{ __('Fechar') }}">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </a>

            <!-- LEFT PANEL: Hero & Navigation (matches user reference image) -->
            <div class="am-hero">
                <div class="am-hero-bg"></div>
                <div class="am-hero-capsules">
                    <div class="am-capsule am-capsule-1"></div>
                    <div class="am-capsule am-capsule-2"></div>
                    <div class="am-capsule am-capsule-3"></div>
                </div>

                <div class="am-hero-content">
                    <!-- Frosted glass brand mark pill -->
                    <div class="am-brand-pill">
                        <img src="{{ asset('logo/icon-portalcientifico.svg') }}" alt="Logo" class="am-brand-icon">
                        <span class="am-brand-text">{{ __('Scientific Portal') }}</span>
                    </div>

                    <!-- Headline & Subtitle -->
                    <h2 class="am-hero-title">{{ __('Track outputs, projects, and reports in one place.') }}</h2>
                    <p class="am-hero-subtitle">
                        {{ __('Sign in to update your profile, manage outputs, and share results with your institution.') }}
                    </p>

                    <!-- Left Menu / Mode Switcher -->
                    <div class="am-mode-nav" role="tablist">
                        <button type="button" class="am-nav-btn {{ $initialTab === 'login' ? 'active' : '' }}" id="tabBtnLogin" onclick="switchAuthTab('login')">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                                <polyline points="10 17 15 12 10 7"></polyline>
                                <line x1="15" y1="12" x2="3" y2="12"></line>
                            </svg>
                            <span>{{ __('INICIAR SESSÃO') }}</span>
                        </button>
                        <button type="button" class="am-nav-btn {{ $initialTab === 'register' ? 'active' : '' }}" id="tabBtnRegister" onclick="switchAuthTab('register')">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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

            <!-- RIGHT PANEL: Forms (Modern shadcn / workspace style) -->
            <div class="am-forms-panel">
                
                <!-- 1. SIGN IN FORM -->
                <div class="am-tab-content {{ $initialTab === 'login' ? 'active' : '' }}" id="amTabLogin">
                    <div class="am-form-header">
                        <h3 class="am-form-title">{{ __('Iniciar Sessão') }}</h3>
                        <p class="am-form-desc">{{ __('Introduza as suas credenciais para aceder ao seu espaço de trabalho.') }}</p>
                    </div>

                    @if ($errors->has('username') || ($errors->has('email') && !old('name')))
                        <div class="am-alert am-alert-error" role="alert">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg>
                            <span>{{ $errors->first('email') ?: $errors->first('username') }}</span>
                        </div>
                    @endif

                    @if (session('status'))
                        <div class="am-alert am-alert-success" role="alert">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                            </svg>
                            <span>{{ session('status') }}</span>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="am-form" id="amLoginForm">
                        @csrf
                        <div class="am-field">
                            <label for="am_login_username" class="am-label">
                                {{ __('Ciência Vitae ID ou Email') }} <span class="am-req">*</span>
                            </label>
                            <input
                                type="text"
                                name="username"
                                id="am_login_username"
                                class="am-input {{ $errors->has('username') ? 'am-input-err' : '' }}"
                                placeholder="Ex.: DXX3-X46X-XEX8 ou email@instituicao.pt"
                                value="{{ old('username') }}"
                                required
                                autocomplete="username"
                            >
                        </div>

                        <div class="am-field">
                            <label for="am_login_password" class="am-label">
                                {{ __('Palavra-passe') }} <span class="am-req">*</span>
                            </label>
                            <div class="am-input-wrap">
                                <input
                                    type="password"
                                    name="password"
                                    id="am_login_password"
                                    class="am-input {{ $errors->has('password') ? 'am-input-err' : '' }}"
                                    placeholder="••••••••"
                                    required
                                    autocomplete="current-password"
                                >
                                <button type="button" class="am-toggle-pw" onclick="togglePasswordVisibility('am_login_password', this)" aria-label="{{ __('Mostrar/Ocultar') }}">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="am-options-row">
                            <label class="am-checkbox-label">
                                <input type="checkbox" name="remember" class="am-checkbox" id="am_remember">
                                <span>{{ __('Lembrar-me') }}</span>
                            </label>

                            <button type="button" class="am-link-btn" onclick="switchAuthTab('recover')">
                                {{ __('Esqueceu-se da palavra-passe?') }}
                            </button>
                        </div>

                        <button type="submit" class="am-submit-btn" id="amLoginSubmitBtn">
                            <span class="am-btn-text">{{ __('INICIAR SESSÃO') }}</span>
                            <span class="am-spinner" style="display: none;" aria-hidden="true"></span>
                        </button>
                    </form>

                    <div class="am-switch-footer">
                        <span>{{ __('Ainda não tem conta?') }}</span>
                        <button type="button" class="am-switch-link" onclick="switchAuthTab('register')">
                            {{ __('Criar registo agora') }} &rarr;
                        </button>
                    </div>
                </div>

                <!-- 2. REGISTER FORM (Clean workspace layout) -->
                <div class="am-tab-content {{ $initialTab === 'register' ? 'active' : '' }}" id="amTabRegister">
                    <div class="am-form-header">
                        <h3 class="am-form-title">{{ __('Registar no Portal Científico') }}</h3>
                        <p class="am-form-desc">{{ __('Preencha os dados abaixo para registar o seu perfil científico.') }}</p>
                    </div>

                    @if ($errors->any() && (old('name') || $errors->has('name') || $errors->has('entities')))
                        <div class="am-alert am-alert-error" role="alert">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg>
                            <span>{{ $errors->first() }}</span>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}" class="am-form" id="amRegisterForm">
                        @csrf
                        <input type="hidden" name="type" value="researcher">

                        <div class="am-grid">
                            <!-- Entidade selection -->
                            <div class="am-col-full">
                                <label for="am_entities" class="am-label">
                                    {{ __('Entidade Institucional') }} <span class="am-req">*</span>
                                </label>
                                <select id="am_entities" name="entities[]" class="am-input am-select" required onchange="handleEntityChange(this)">
                                    <option value="" disabled {{ empty(old('entities')) ? 'selected' : '' }}>{{ __('Selecione a sua entidade') }}</option>
                                    @foreach ($entitiesList as $entity)
                                        <option value="{{ $entity->name }}" {{ in_array($entity->name, (array) old('entities', [])) ? 'selected' : '' }}>
                                            {{ $entity->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Custom entity input if other -->
                            <div class="am-col-full" id="amCustomEntityWrap" style="{{ in_array('OTHER', (array) old('entities', [])) ? 'display: block;' : 'display: none;' }}">
                                <label for="am_custom_entity" class="am-label">
                                    {{ __('Designação da Outra Entidade') }} <span class="am-req">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="am_custom_entity"
                                    name="custom_entity"
                                    class="am-input"
                                    placeholder="Ex.: ISLA GAIA / CEOS.PP"
                                    value="{{ old('custom_entity') }}"
                                >
                            </div>

                            <!-- Name -->
                            <div class="am-col-half">
                                <label for="am_reg_name" class="am-label">
                                    {{ __('Nome Completo') }} <span class="am-req">*</span>
                                </label>
                                <input
                                    type="text"
                                    name="name"
                                    id="am_reg_name"
                                    class="am-input {{ $errors->has('name') ? 'am-input-err' : '' }}"
                                    placeholder="Ex.: Ana Silva"
                                    value="{{ old('name') }}"
                                    required
                                    autocomplete="name"
                                >
                            </div>

                            <!-- Email -->
                            <div class="am-col-half">
                                <label for="am_reg_email" class="am-label">
                                    {{ __('Email Institucional') }} <span class="am-req">*</span>
                                </label>
                                <input
                                    type="email"
                                    name="email"
                                    id="am_reg_email"
                                    class="am-input {{ $errors->has('email') ? 'am-input-err' : '' }}"
                                    placeholder="email@instituicao.pt"
                                    value="{{ old('email') }}"
                                    required
                                    autocomplete="email"
                                >
                            </div>

                            <!-- Ciencia Vitae ID -->
                            <div class="am-col-full">
                                <label for="am_reg_cv" class="am-label">
                                    {{ __('Ciência Vitae ID') }}
                                </label>
                                <input
                                    type="text"
                                    name="ciencia_vitae"
                                    id="am_reg_cv"
                                    class="am-input {{ $errors->has('ciencia_vitae') ? 'am-input-err' : '' }}"
                                    placeholder="Ex.: DXX3-X46X-XEX8 (opcional)"
                                    value="{{ old('ciencia_vitae') }}"
                                >
                            </div>

                            <!-- Password -->
                            <div class="am-col-half">
                                <label for="am_reg_password" class="am-label">
                                    {{ __('Palavra-passe') }} <span class="am-req">*</span>
                                </label>
                                <div class="am-input-wrap">
                                    <input
                                        type="password"
                                        name="password"
                                        id="am_reg_password"
                                        class="am-input {{ $errors->has('password') ? 'am-input-err' : '' }}"
                                        placeholder="••••••••"
                                        required
                                        autocomplete="new-password"
                                    >
                                    <button type="button" class="am-toggle-pw" onclick="togglePasswordVisibility('am_reg_password', this)" aria-label="{{ __('Mostrar/Ocultar') }}">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Confirm Password -->
                            <div class="am-col-half">
                                <label for="am_reg_pw_conf" class="am-label">
                                    {{ __('Confirmar Palavra-passe') }} <span class="am-req">*</span>
                                </label>
                                <div class="am-input-wrap">
                                    <input
                                        type="password"
                                        name="password_confirmation"
                                        id="am_reg_pw_conf"
                                        class="am-input"
                                        placeholder="••••••••"
                                        required
                                        autocomplete="new-password"
                                    >
                                    <button type="button" class="am-toggle-pw" onclick="togglePasswordVisibility('am_reg_pw_conf', this)" aria-label="{{ __('Mostrar/Ocultar') }}">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="am-submit-btn mt-3" id="amRegisterSubmitBtn">
                            <span class="am-btn-text">{{ __('CONCLUIR REGISTO') }}</span>
                            <span class="am-spinner" style="display: none;" aria-hidden="true"></span>
                        </button>
                    </form>

                    <div class="am-switch-footer">
                        <span>{{ __('Já tem uma conta?') }}</span>
                        <button type="button" class="am-switch-link" onclick="switchAuthTab('login')">
                            {{ __('Iniciar Sessão') }} &rarr;
                        </button>
                    </div>
                </div>

                <!-- 3. RECOVER PASSWORD FORM -->
                <div class="am-tab-content {{ $initialTab === 'recover' ? 'active' : '' }}" id="amTabRecover">
                    <div class="am-form-header">
                        <h3 class="am-form-title">{{ __('Recuperar Palavra-passe') }}</h3>
                        <p class="am-form-desc">{{ __('Introduza o seu email institucional para receber um link de reposição.') }}</p>
                    </div>

                    @if ($errors->has('email') && !old('name') && !old('username'))
                        <div class="am-alert am-alert-error" role="alert">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg>
                            <span>{{ $errors->first('email') }}</span>
                        </div>
                    @endif

                    @if (session('status'))
                        <div class="am-alert am-alert-success" role="alert">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                            </svg>
                            <span>{{ session('status') }}</span>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}" class="am-form" id="amRecoverForm">
                        @csrf
                        <div class="am-field">
                            <label for="am_recover_email" class="am-label">
                                {{ __('Email') }} <span class="am-req">*</span>
                            </label>
                            <input
                                type="email"
                                name="email"
                                id="am_recover_email"
                                class="am-input {{ $errors->has('email') ? 'am-input-err' : '' }}"
                                placeholder="name@example.com"
                                value="{{ old('email') }}"
                                required
                                autocomplete="email"
                            >
                        </div>

                        <button type="submit" class="am-submit-btn" id="amRecoverSubmitBtn">
                            <span class="am-btn-text">{{ __('Enviar Link de Reposição') }}</span>
                            <span class="am-spinner" style="display: none;" aria-hidden="true"></span>
                        </button>
                    </form>

                    <div class="am-switch-footer">
                        <button type="button" class="am-switch-link" onclick="switchAuthTab('login')">
                            &larr; {{ __('Voltar ao início de sessão') }}
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <style>
        .am-overlay {
            position: fixed;
            inset: 0;
            z-index: 1060;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
            font-family: 'Manrope', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            overflow-y: auto;
        }

        .am-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.55);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            animation: am-fade-in 0.25s ease-out forwards;
        }

        .am-dialog {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 1040px;
            margin: auto;
            animation: am-slide-up 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes am-fade-in {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes am-slide-up {
            from { opacity: 0; transform: translateY(22px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .am-container {
            position: relative;
            background: #ffffff;
            border-radius: 24px;
            border: 1px solid rgba(226, 232, 240, 0.9);
            box-shadow: 0 28px 80px -16px rgba(15, 23, 42, 0.22), 0 10px 28px -6px rgba(15, 23, 42, 0.08);
            overflow: hidden;
            display: grid;
            grid-template-columns: 0.95fr 1.05fr;
            min-height: 600px;
        }

        .am-close-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 10;
            background: rgba(241, 245, 249, 0.85);
            border: 1px solid rgba(203, 213, 225, 0.6);
            color: #475569;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.15s ease;
        }

        .am-close-btn:hover {
            background: #e2e8f0;
            color: #0f172a;
            transform: scale(1.05);
        }

        /* LEFT HERO PANEL (Reference image matching) */
        .am-hero {
            position: relative;
            background: linear-gradient(150deg, #163622 0%, #1f502f 40%, #173f25 100%);
            color: #ffffff;
            padding: 48px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            overflow: hidden;
            isolation: isolate;
        }

        .am-hero-bg {
            position: absolute;
            inset: 0;
            background: url('{{ asset('logo/image-portal.png') }}') center/cover no-repeat;
            opacity: 0.22;
            mix-blend-mode: luminosity;
            z-index: 0;
        }

        /* Pill/capsule overlapping background textures from user screenshot */
        .am-hero-capsules {
            position: absolute;
            inset: 0;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
        }

        .am-capsule {
            position: absolute;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(255, 255, 255, 0.04);
            backdrop-filter: blur(2px);
            transform: rotate(-35deg);
        }

        .am-capsule-1 {
            width: 280px;
            height: 120px;
            top: -30px;
            left: -40px;
        }

        .am-capsule-2 {
            width: 380px;
            height: 160px;
            bottom: -50px;
            right: -70px;
        }

        .am-capsule-3 {
            width: 320px;
            height: 140px;
            top: 45%;
            left: 20%;
        }

        .am-hero-content {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        /* Frosted glass brand mark pill */
        .am-brand-pill {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            padding: 8px 18px;
            background: rgba(255, 255, 255, 0.18);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.26);
            border-radius: 100px;
            box-shadow: 0 10px 28px rgba(0, 0, 0, 0.2);
            margin-bottom: 24px;
        }

        .am-brand-icon {
            width: 28px;
            height: 28px;
        }

        .am-brand-text {
            font-size: 0.95rem;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: 0.2px;
        }

        .am-hero-title {
            font-size: 2rem;
            font-weight: 800;
            line-height: 1.22;
            color: #ffffff;
            letter-spacing: -0.02em;
            margin: 0 0 14px 0;
            text-shadow: 0 4px 18px rgba(0, 0, 0, 0.35);
        }

        .am-hero-subtitle {
            font-size: 0.975rem;
            color: #e2f0e6;
            line-height: 1.6;
            margin: 0 0 32px 0;
            max-width: 380px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.28);
        }

        /* Mode Switcher Nav (Tabs) */
        .am-mode-nav {
            display: flex;
            background: rgba(0, 0, 0, 0.24);
            border: 1px solid rgba(255, 255, 255, 0.14);
            padding: 5px;
            border-radius: 100px;
            width: 100%;
            max-width: 340px;
            gap: 4px;
        }

        .am-nav-btn {
            flex: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 16px;
            border-radius: 100px;
            border: none;
            background: transparent;
            color: #d1e7d8;
            font-size: 0.8125rem;
            font-weight: 700;
            letter-spacing: 0.4px;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .am-nav-btn.active {
            background: #ffffff;
            color: #173f25;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.22);
        }

        .am-nav-btn:hover:not(.active) {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.08);
        }

        /* RIGHT FORMS PANEL */
        .am-forms-panel {
            padding: 48px 44px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            overflow-y: auto;
            max-height: 85vh;
        }

        .am-tab-content {
            display: none;
            animation: am-content-fade 0.2s ease-out forwards;
        }

        .am-tab-content.active {
            display: block;
        }

        @keyframes am-content-fade {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .am-form-header {
            margin-bottom: 24px;
            text-align: left;
        }

        .am-form-title {
            font-size: 1.45rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 6px 0;
            letter-spacing: -0.015em;
        }

        .am-form-desc {
            font-size: 0.875rem;
            color: #64748b;
            margin: 0;
            line-height: 1.45;
        }

        .am-form {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .am-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 14px 16px;
        }

        .am-col-full { grid-column: span 2; }
        .am-col-half { grid-column: span 1; }

        .am-field {
            display: flex;
            flex-direction: column;
            gap: 6px;
            text-align: left;
        }

        .am-label {
            font-size: 0.8125rem;
            font-weight: 600;
            color: #1e293b;
        }

        .am-req {
            color: #ef4444;
        }

        .am-input-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }

        .am-input-wrap .am-input {
            padding-right: 42px;
        }

        .am-input::-ms-reveal,
        .am-input::-ms-clear {
            display: none;
        }

        .am-input {
            width: 100%;
            height: 42px;
            padding: 8px 13px;
            font-size: 0.875rem;
            border: 1px solid #cbd5e1;
            border-radius: 9px;
            background-color: #ffffff;
            color: #0f172a;
            outline: none;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .am-select {
            cursor: pointer;
            appearance: auto;
        }

        .am-input::placeholder {
            color: #94a3b8;
        }

        .am-input:focus {
            border-color: #2e7a3f;
            box-shadow: 0 0 0 3px rgba(46, 122, 63, 0.16);
        }

        .am-input-err {
            border-color: #ef4444;
        }

        .am-input-err:focus {
            border-color: #ef4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.16);
        }

        .am-toggle-pw {
            position: absolute;
            right: 8px;
            background: transparent;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 6px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2;
            transition: color 0.15s ease, background-color 0.15s ease;
        }

        .am-toggle-pw:hover {
            color: #334155;
            background-color: rgba(0, 0, 0, 0.04);
        }

        .am-options-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.8125rem;
            margin-top: 2px;
        }

        .am-checkbox-label {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            cursor: pointer;
            color: #475569;
            user-select: none;
        }

        .am-checkbox {
            width: 16px;
            height: 16px;
            accent-color: #2e7a3f;
            cursor: pointer;
            border-radius: 4px;
        }

        .am-link-btn {
            background: transparent;
            border: none;
            color: #2e7a3f;
            font-size: 0.8125rem;
            font-weight: 600;
            cursor: pointer;
            padding: 0;
            text-decoration: none;
        }

        .am-link-btn:hover {
            color: #1a4925;
            text-decoration: underline;
        }

        .am-submit-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 44px;
            padding: 10px 16px;
            font-size: 0.875rem;
            font-weight: 700;
            letter-spacing: 0.3px;
            border: none;
            border-radius: 10px;
            color: #ffffff;
            background: linear-gradient(115deg, #2e7a3f, #1f572d);
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(31, 87, 45, 0.25);
            transition: all 0.15s ease;
            gap: 8px;
            margin-top: 6px;
        }

        .am-submit-btn:hover {
            opacity: 0.94;
            box-shadow: 0 6px 18px rgba(31, 87, 45, 0.32);
            transform: translateY(-1px);
        }

        .am-submit-btn:active {
            transform: translateY(0);
        }

        .am-submit-btn:disabled {
            opacity: 0.65;
            cursor: not-allowed;
            transform: none;
        }

        .am-spinner {
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top-color: #ffffff;
            border-radius: 50%;
            animation: am-spin 0.7s linear infinite;
        }

        @keyframes am-spin {
            to { transform: rotate(360deg); }
        }

        .am-switch-footer {
            margin-top: 22px;
            text-align: center;
            font-size: 0.8125rem;
            color: #64748b;
        }

        .am-switch-link {
            background: transparent;
            border: none;
            color: #2e7a3f;
            font-weight: 700;
            cursor: pointer;
            padding: 0 0 0 4px;
            font-size: 0.8125rem;
        }

        .am-switch-link:hover {
            color: #1a4925;
            text-decoration: underline;
        }

        .am-alert {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            border-radius: 10px;
            font-size: 0.8125rem;
            font-weight: 500;
            line-height: 1.4;
            margin-bottom: 16px;
            text-align: left;
        }

        .am-alert-success {
            background-color: #ecfdf5;
            border: 1px solid #a7f3d0;
            color: #065f46;
        }

        .am-alert-error {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
        }

        @media (max-width: 900px) {
            .am-container {
                grid-template-columns: 1fr;
                min-height: auto;
            }
            .am-hero {
                padding: 36px 24px;
            }
            .am-hero-title {
                font-size: 1.6rem;
            }
            .am-forms-panel {
                padding: 32px 24px;
            }
            .am-grid {
                grid-template-columns: 1fr;
            }
            .am-col-half {
                grid-column: span 1;
            }
        }
    </style>

    <script>
        function switchAuthTab(tabName) {
            const tabs = ['login', 'register', 'recover'];
            tabs.forEach(t => {
                const content = document.getElementById('amTab' + t.charAt(0).toUpperCase() + t.slice(1));
                if (content) content.classList.remove('active');
            });

            const activeContent = document.getElementById('amTab' + tabName.charAt(0).toUpperCase() + tabName.slice(1));
            if (activeContent) activeContent.classList.add('active');

            // Update left nav button highlights
            const loginBtn = document.getElementById('tabBtnLogin');
            const regBtn = document.getElementById('tabBtnRegister');
            if (loginBtn && regBtn) {
                if (tabName === 'login') {
                    loginBtn.classList.add('active');
                    regBtn.classList.remove('active');
                } else if (tabName === 'register') {
                    regBtn.classList.add('active');
                    loginBtn.classList.remove('active');
                } else {
                    loginBtn.classList.remove('active');
                    regBtn.classList.remove('active');
                }
            }

            // Focus on primary input of active tab
            setTimeout(() => {
                if (tabName === 'login') document.getElementById('am_login_username')?.focus();
                else if (tabName === 'register') document.getElementById('am_entities')?.focus();
                else if (tabName === 'recover') document.getElementById('am_recover_email')?.focus();
            }, 100);
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
                customWrap.style.display = 'block';
                document.getElementById('am_custom_entity')?.focus();
            } else {
                customWrap.style.display = 'none';
            }
        }

        (function () {
            const modal = document.getElementById('authModal');
            const backdrop = document.getElementById('amBackdrop');
            const closeBtn = document.getElementById('amCloseBtn');

            function openModal(tab = 'login') {
                if (!modal) return;
                modal.style.display = 'flex';
                switchAuthTab(tab);
                document.body.style.overflow = 'hidden';
            }

            function closeModal() {
                if (!modal) return;
                @if ($isStandalone)
                    window.location.href = "{{ url('/') }}";
                @else
                    modal.style.display = 'none';
                    document.body.style.overflow = '';
                @endif
            }

            @if (!$isStandalone)
                closeBtn?.addEventListener('click', closeModal);
                backdrop?.addEventListener('click', closeModal);

                document.addEventListener('keydown', function (e) {
                    if (e.key === 'Escape' && modal && modal.style.display !== 'none') {
                        closeModal();
                    }
                });
            @endif

            // Loading state handlers
            ['amLoginForm', 'amRegisterForm', 'amRecoverForm'].forEach(formId => {
                const f = document.getElementById(formId);
                f?.addEventListener('submit', function () {
                    const btn = f.querySelector('.am-submit-btn');
                    if (btn) {
                        btn.disabled = true;
                        const spinner = btn.querySelector('.am-spinner');
                        const text = btn.querySelector('.am-btn-text');
                        if (spinner) spinner.style.display = 'inline-block';
                        if (text) text.textContent = '{{ __("A processar...") }}';
                    }
                });
            });

            window.openAuthModal = openModal;
            window.closeAuthModal = closeModal;
        })();
    </script>
</div>
