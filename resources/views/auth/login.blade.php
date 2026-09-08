<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Manrope:wght@500;600;700&display=swap');

        body.saas {
            font-family: 'Manrope', 'Segoe UI', sans-serif;
            background: radial-gradient(circle at 10% 15%, #d8f0e1 0, #eef2f7 28%, #f6f7fb 60%);
        }

        .login-shell {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 28px 16px;
            position: relative;
            overflow: hidden;
        }

        .login-shell::before,
        .login-shell::after {
            content: '';
            position: absolute;
            border-radius: 900px;
            filter: blur(72px);
            opacity: 0.2;
            z-index: 0;
        }

        .login-shell::before {
            width: 420px;
            height: 420px;
            background: #b7e3c4;
            top: -120px;
            left: -140px;
        }

        .login-shell::after {
            width: 460px;
            height: 460px;
            background: #c6d6ff;
            bottom: -140px;
            right: -120px;
        }

        .login-card {
            width: min(1320px, 98vw);
            background: rgba(255, 255, 255, 0.96);
            border-radius: 22px;
            box-shadow: 0 28px 78px rgba(26, 63, 23, 0.22);
            overflow: hidden;
            display: grid;
            grid-template-columns: 1.05fr 0.95fr;
            position: relative;
            z-index: 1;
        }

        .login-form-wrap { padding: 56px 64px; }

        .login-hero {
            position: relative;
            color: #fff;
            background: radial-gradient(circle at 15% 20%, rgba(94, 171, 111, 0.42), transparent 46%),
                linear-gradient(145deg, #1a3c27, #246438 45%, #1d4d30 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 56px;
            isolation: isolate;
        }

        .login-hero::after {
            content: '';
            position: absolute;
            inset: 0;
            background: url('{{ asset('logo/image-portal.png') }}') center/cover no-repeat;
            opacity: 0.26;
            z-index: 0;
        }

        .login-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(20, 46, 28, 0.5), rgba(16, 36, 22, 0.28));
            z-index: 0;
        }

        .login-hero-content {
            position: relative;
            z-index: 1;
            max-width: 520px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .brand-mark {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.22);
            margin-bottom: 18px;
            box-shadow: 0 16px 36px rgba(0, 0, 0, 0.22);
        }

        .login-card label { font-weight: 600; color: #1f2d24; }

        .form-control,
        .form-select,
        .select2-selection {
            border-radius: 10px !important;
            border: 1px solid #d5dfd7 !important;
            min-height: 46px;
            box-shadow: none !important;
        }

        .form-control::placeholder {
            color: #6b7a7281;
            font-style: italic;   
        }

        .btn-primary-login {
            background: linear-gradient(115deg, #2e7a3f, #1f572d);
            border: none;
            border-radius: 12px;
            padding: 12px 16px;
            font-weight: 700;
            letter-spacing: 0.2px;
        }

        .hero-headline {
            font-size: 36px;
            line-height: 1.2;
            text-shadow: 0 8px 24px rgba(0, 0, 0, 0.35);
        }

        .hero-body {
            font-size: 15px;
            color: #e9f3ec;
            line-height: 1.7;
            text-shadow: 0 6px 18px rgba(0, 0, 0, 0.3);
        }

        .btn-hero-cta {
            background: #ffffff;
            color: #1f572d !important;
            border: 1px solid #e0e8e2;
            border-radius: 14px;
            padding: 12px 22px;
            min-width: 410px;
            font-weight: 700;
            box-shadow: 0 16px 38px rgba(12, 35, 21, 0.28);
            transition: transform 180ms ease, box-shadow 180ms ease, filter 180ms ease;
        }

        .btn-hero-cta:hover,
        .btn-hero-cta:focus,
        .btn-hero-cta:active {
            background: #ffffff;
            color: #1f572d !important;
            border-color: #e0e8e2;
            box-shadow: 0 16px 38px rgba(12, 35, 21, 0.28);
            transform: none;
            filter: none;
        }


        .hint-text { color: #5a6b63; }

        @media (max-width: 992px) {
            .login-card { grid-template-columns: 1fr; }
            .login-hero { order: -1; padding: 36px; }
            .login-form-wrap { padding: 36px 26px; }
        }
    </style>

    <div class="login-shell">
        <div class="login-card">
            <div class="login-form-wrap">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <img src="{{ asset('logo/icon-portalcientifico.svg') }}" alt="Logo Portal" style="width: 46px; height: 46px;">
                        <div>
                            <div style="font-weight: 800; font-size: 24px; color: #1d3a24;">{{ __('Portal Científico') }}</div>
                        </div>
                    </div>
                    @if ($errors->any())
                        <span class="badge bg-danger-subtle text-danger" style="border: 1px solid #f2b8b5;">{{ __('Credenciais inválidas') }}</span>
                    @endif
                </div>

                <form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate>
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">{{ __('Ciencia Vitae ID') }}</label>
                        <input type="text" name="username" id="email" class="form-control" required placeholder="Ex.: DXX3-X46X-XEX8">
                    </div>

                    <div class="mb-2">
                        <label for="password" class="form-label">{{ __('Password') }}</label>
                        <input type="password" name="password" id="password" class="form-control" required placeholder="••••••••">
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label" for="remember">{{ __('Remember Me') }}</label>
                        </div>
                        @if (Route::has('password.request'))
                            <a class="small text-decoration-none" href="{{ route('password.request') }}">{{ __('Forgot Password?') }}</a>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-primary-login w-100 text-white">{{ __('INICIAR SESSÃO') }}</button>
                </form>
            </div>

            <div class="login-hero">
                <div class="login-hero-content" style="max-width: 560px;">
                    <div class="brand-mark mb-3">
                        <img src="{{ asset('logo/icon-portalcientifico.svg') }}" alt="Brand" style="width: 32px; height: 32px;">
                        <span class="fw-semibold">{{ __('Portal Científico') }}</span>
                    </div>
                    <h3 class="fw-bold hero-headline">{{ __('Acompanhe produção, projetos e relatórios num só lugar.') }}</h3>
                    <p class="mt-3 mb-4 hero-body">
                        {{ __('Entre para atualizar o seu perfil, gerir outputs e partilhar resultados com a instituição.') }}
                    </p>
                    <a href="{{ route('register') }}" class="btn btn-hero-cta">
                        {{ __('Registar') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <x-alert-component id="alert-message" error-message="1" message="Incorrect Email or Password!" />
    @endif

</x-guest-layout>
