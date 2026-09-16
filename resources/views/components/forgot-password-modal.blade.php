@php
    $errors = $errors ?? session('errors', new \Illuminate\Support\ViewErrorBag());
@endphp

<div id="forgotPasswordModal" class="fp-modal" aria-hidden="true" role="dialog" aria-labelledby="forgotPasswordTitle" style="display: none;">
    <div class="fp-modal-backdrop" id="fpModalBackdrop"></div>

    <div class="fp-modal-dialog" role="document">
        <div class="fp-card">
            <!-- Close button -->
            <button type="button" class="fp-close-btn" id="fpCloseModalBtn" aria-label="{{ __('Fechar') }}">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>

            <!-- Card Header -->
            <div class="fp-header">
                <a href="/" aria-label="{{ __('Início') }}" class="fp-brand-link">
                    <img src="{{ asset('logo/icon-portalcientifico.svg') }}" alt="Portal Científico" class="fp-brand-icon">
                </a>
                <h1 id="forgotPasswordTitle" class="fp-title">{{ __('Recuperar Palavra-passe') }}</h1>
                <p class="fp-subtitle">{{ __('Introduza o seu email para receber uma ligação de reposição.') }}</p>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('password.email') }}" class="fp-form" id="forgotPasswordForm">
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
                    <label for="fp_reset_email" class="fp-label">{{ __('Email') }}</label>
                    <input
                        type="email"
                        id="fp_reset_email"
                        name="email"
                        class="fp-input {{ $errors->has('email') ? 'fp-input-error' : '' }}"
                        placeholder="name@example.com"
                        value="{{ old('email') }}"
                        required
                        autocomplete="email"
                    >
                </div>

                <button type="submit" class="fp-submit-btn" id="fpSubmitBtn">
                    <span id="fpSubmitText">{{ __('Enviar Link de Reposição') }}</span>
                    <span id="fpSubmitSpinner" class="fp-spinner" style="display: none;" aria-hidden="true"></span>
                </button>
            </form>

            <div class="fp-footer">
                <p class="fp-footer-text">{{ __("Enviaremos uma ligação segura para repor a sua palavra-passe.") }}</p>
            </div>
        </div>
    </div>

    <style>
        .fp-modal {
            position: fixed;
            inset: 0;
            z-index: 1060;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
            font-family: inherit;
        }

        .fp-modal-backdrop {
            position: absolute;
            inset: 0;
            background: rgba(15, 23, 42, 0.55);
            animation: fp-fade-in 0.2s ease-out forwards;
        }

        .fp-modal-dialog {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 440px;
            margin: auto;
            animation: fp-slide-up 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes fp-fade-in {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes fp-slide-up {
            from {
                opacity: 0;
                transform: translateY(18px) scale(0.97);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .fp-card {
            position: relative;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            padding: 32px 28px 24px 28px;
            box-shadow: 0 24px 60px -12px rgba(15, 23, 42, 0.18), 0 8px 20px -4px rgba(15, 23, 42, 0.08);
            color: #0f172a;
        }

        .fp-close-btn {
            position: absolute;
            top: 18px;
            right: 18px;
            background: transparent;
            border: none;
            color: #64748b;
            padding: 6px;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.15s ease, background 0.15s ease;
        }

        .fp-close-btn:hover {
            color: #0f172a;
            background: #f1f5f9;
        }

        .fp-brand-link {
            display: inline-flex;
            margin-bottom: 12px;
        }

        .fp-brand-icon {
            width: 38px;
            height: 38px;
        }

        .fp-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 6px 0;
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
            height: 42px;
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
            font-style: normal;
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
            box-shadow: 0 4px 12px rgba(31, 87, 45, 0.25);
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
            margin-top: 20px;
            text-align: center;
        }

        .fp-footer-text {
            font-size: 0.8125rem;
            color: #64748b;
            margin: 0;
            line-height: 1.4;
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

    <script>
        (function () {
            const modal = document.getElementById('forgotPasswordModal');
            const backdrop = document.getElementById('fpModalBackdrop');
            const closeBtn = document.getElementById('fpCloseModalBtn');
            const emailInput = document.getElementById('fp_reset_email');
            const form = document.getElementById('forgotPasswordForm');
            const submitBtn = document.getElementById('fpSubmitBtn');
            const submitSpinner = document.getElementById('fpSubmitSpinner');
            const submitText = document.getElementById('fpSubmitText');

            function openModal() {
                if (!modal) return;
                modal.style.display = 'flex';
                modal.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
                setTimeout(() => emailInput?.focus(), 120);
            }

            function closeModal() {
                if (!modal) return;
                modal.style.display = 'none';
                modal.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
            }

            closeBtn?.addEventListener('click', closeModal);
            backdrop?.addEventListener('click', closeModal);

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && modal && modal.style.display !== 'none') {
                    closeModal();
                }
            });

            form?.addEventListener('submit', function () {
                if (submitBtn) {
                    submitBtn.disabled = true;
                    if (submitSpinner) submitSpinner.style.display = 'inline-block';
                    if (submitText) submitText.textContent = '{{ __("A enviar...") }}';
                }
            });

            window.openForgotPasswordModal = openModal;
            window.closeForgotPasswordModal = closeModal;

            // Automatically open modal if there was an email validation error or status from password reset
            @if ($errors->has('email') || session('status'))
                openModal();
            @endif
        })();
    </script>
</div>
