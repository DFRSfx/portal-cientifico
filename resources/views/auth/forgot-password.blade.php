<x-guest-layout>
    @php
        $errors = $errors ?? session('errors', new \Illuminate\Support\ViewErrorBag());
    @endphp

    <x-slot:title>
        {{ __('Recuperar Palavra-passe') }}
    </x-slot>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Manrope:wght@500;600;700&display=swap');

        body.saas {
            font-family: 'Manrope', 'Segoe UI', sans-serif;
            background: radial-gradient(circle at 10% 15%, #d8f0e1 0, #eef2f7 28%, #f6f7fb 60%);
        }

        .fp-page-shell {
            min-height: calc(100vh - 120px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 16px;
            position: relative;
        }

        .fp-page-card {
            width: 100%;
            max-width: 440px;
            background: rgba(255, 255, 255, 0.98);
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            padding: 36px 32px 28px 32px;
            box-shadow: 0 24px 60px -12px rgba(15, 23, 42, 0.16), 0 8px 20px -4px rgba(15, 23, 42, 0.06);
            color: #0f172a;
        }

        .fp-brand-link {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            margin-bottom: 8px;
        }

        .fp-brand-icon {
            width: 40px;
            height: 40px;
        }

        .fp-title {
            font-size: 1.35rem;
            font-weight: 700;
            color: #0f172a;
            margin: 10px 0 6px 0;
            letter-spacing: -0.015em;
        }

        .fp-subtitle {
            font-size: 0.875rem;
            color: #64748b;
            margin: 0;
            line-height: 1.45;
        }

        .fp-form {
            margin-top: 24px;
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .fp-input-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
            text-align: left;
        }

        .fp-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: #1e293b;
        }

        .fp-input {
            width: 100%;
            height: 44px;
            padding: 8px 14px;
            font-size: 0.875rem;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            background-color: #ffffff;
            color: #0f172a;
            outline: none;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .fp-input::placeholder {
            color: #94a3b8;
        }

        .fp-input:focus {
            border-color: #2e7a3f;
            box-shadow: 0 0 0 3px rgba(46, 122, 63, 0.18);
        }

        .fp-input-error {
            border-color: #ef4444;
        }

        .fp-input-error:focus {
            border-color: #ef4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.18);
        }

        .fp-submit-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 44px;
            padding: 10px 16px;
            font-size: 0.875rem;
            font-weight: 700;
            letter-spacing: 0.2px;
            border: none;
            border-radius: 10px;
            color: #ffffff;
            background: linear-gradient(115deg, #2e7a3f, #1f572d);
            cursor: pointer;
            transition: opacity 0.15s ease, transform 0.15s ease, box-shadow 0.15s ease;
            box-shadow: 0 4px 14px rgba(31, 87, 45, 0.25);
            gap: 8px;
        }

        .fp-submit-btn:hover {
            opacity: 0.94;
            box-shadow: 0 6px 18px rgba(31, 87, 45, 0.32);
            transform: translateY(-1px);
        }

        .fp-submit-btn:active {
            transform: translateY(0);
        }

        .fp-submit-btn:disabled {
            opacity: 0.65;
            cursor: not-allowed;
            transform: none;
        }

        .fp-spinner {
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top-color: #ffffff;
            border-radius: 50%;
            animation: fp-spin 0.7s linear infinite;
        }

        @keyframes fp-spin {
            to { transform: rotate(360deg); }
        }

        .fp-footer {
            margin-top: 22px;
            text-align: center;
        }

        .fp-footer-text {
            font-size: 0.8125rem;
            color: #64748b;
            margin: 0 0 12px 0;
            line-height: 1.4;
        }

        .fp-back-link {
            font-size: 0.8125rem;
            font-weight: 600;
            color: #2e7a3f;
            text-decoration: none;
            transition: color 0.15s ease;
        }

        .fp-back-link:hover {
            color: #1a4925;
            text-decoration: underline;
        }

        .fp-alert {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            border-radius: 10px;
            font-size: 0.8125rem;
            font-weight: 500;
            line-height: 1.4;
        }

        .fp-alert-success {
            background-color: #ecfdf5;
            border: 1px solid #a7f3d0;
            color: #065f46;
        }

        .fp-alert-error {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
        }
    </style>

    <div class="fp-page-shell">
        <div class="fp-page-card">
            <!-- Header -->
            <div>
                <a href="/" aria-label="{{ __('Ir para a página inicial') }}" class="fp-brand-link">
                    <img src="{{ asset('logo/icon-portalcientifico.svg') }}" alt="Portal Científico" class="fp-brand-icon">
                </a>
                <h1 class="fp-title">{{ __('Recuperar Palavra-passe') }}</h1>
                <p class="fp-subtitle">{{ __('Introduza o seu email para receber uma ligação de reposição.') }}</p>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('password.email') }}" class="fp-form" id="forgotPasswordPageForm">
                @csrf

                @if (session('status'))
                    <div class="fp-alert fp-alert-success" role="alert">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                @if ($errors->has('email'))
                    <div class="fp-alert fp-alert-error" role="alert">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                        <span>{{ $errors->first('email') }}</span>
                    </div>
                @endif

                <div class="fp-input-group">
                    <label for="email" class="fp-label">{{ __('Email') }}</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="fp-input {{ $errors->has('email') ? 'fp-input-error' : '' }}"
                        placeholder="name@example.com"
                        value="{{ old('email') }}"
                        required
                        autocomplete="email"
                        autofocus
                    >
                </div>

                <button type="submit" class="fp-submit-btn" id="fpPageSubmitBtn">
                    <span id="fpPageSubmitText">{{ __('Enviar Link de Reposição') }}</span>
                    <span id="fpPageSubmitSpinner" class="fp-spinner" style="display: none;" aria-hidden="true"></span>
                </button>
            </form>

            <div class="fp-footer">
                <p class="fp-footer-text">{{ __("Enviaremos uma ligação para repor a sua palavra-passe.") }}</p>
                <a href="{{ route('login') }}" class="fp-back-link">
                    &larr; {{ __('Voltar ao início de sessão') }}
                </a>
            </div>
        </div>
    </div>

    <script>
        (function() {
            const form = document.getElementById('forgotPasswordPageForm');
            const btn = document.getElementById('fpPageSubmitBtn');
            const spinner = document.getElementById('fpPageSubmitSpinner');
            const text = document.getElementById('fpPageSubmitText');

            form?.addEventListener('submit', function() {
                if (btn) {
                    btn.disabled = true;
                    if (spinner) spinner.style.display = 'inline-block';
                    if (text) text.textContent = '{{ __("A enviar...") }}';
                }
            });
        })();
    </script>
</x-guest-layout>
