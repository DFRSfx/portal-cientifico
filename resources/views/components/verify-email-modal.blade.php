@props([
    'isStandalone' => false,
    'show' => null,
])

@php
    $user = auth()->user();
    // In overlay mode, never auto-open on every page by default.
    // Only show if explicitly requested or triggered via session flash.
    $shouldShow = $show ?? (session('open_verify_email') || session('status') == 'verification-link-sent');
@endphp

@if ($isStandalone)
    {{-- STANDALONE PAGE MODE: Renders directly in page flow on soft background, no fixed backdrop --}}
    <div class="vem-page-shell">
        <div class="vem-card">
            <!-- Close / Exit button back to Home -->
            <a href="{{ url('/') }}" class="vem-close-btn" aria-label="{{ __('Fechar') }}" title="{{ __('Voltar ao Início') }}">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </a>

            <!-- Card Header -->
            <div class="vem-card-header">
                <h1 class="vem-title">{{ __('Verify your email address') }}</h1>
                <p class="vem-subtitle">
                    @if ($user && $user->hasVerifiedEmail())
                        {{ __('O seu email já foi confirmado.') }}
                    @else
                        {{ __("We've sent a verification link to") }} <span class="vem-email-badge">{{ $user?->email ?? 'seu email' }}</span>. {{ __('Please check your inbox and click the link to verify your email address.') }}
                    @endif
                </p>
            </div>

            <!-- Card Body -->
            <div class="vem-card-body">
                <div class="vem-body-inner">
                    <!-- Circular Mail Icon -->
                    <div class="vem-icon-circle">
                        @if ($user && $user->hasVerifiedEmail())
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                            </svg>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mail" aria-hidden="true">
                                <path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7"></path>
                                <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                            </svg>
                        @endif
                    </div>

                    <div class="vem-content-stack">
                        @if (session('status') == 'verification-link-sent')
                            <div class="vem-alert-banner" role="alert">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                </svg>
                                <span>{{ __('A new verification link has been sent to the email address you provided during registration.') }}</span>
                            </div>
                        @endif

                        <div class="vem-callout">
                            @if ($user && $user->hasVerifiedEmail())
                                <div class="vem-callout-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #059669;" aria-hidden="true">
                                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                                        <polyline points="9 12 11 14 15 10"></polyline>
                                    </svg>
                                </div>
                                <div class="vem-callout-content">
                                    <p class="vem-callout-title">{{ __('Aguarde aprovação') }}</p>
                                    <p class="vem-callout-text">{{ __('O seu email já foi confirmado. Aguarde a aprovação do administrador para aceder ao dashboard.') }}</p>
                                </div>
                            @else
                                <div class="vem-callout-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <path d="M12 6v6l4 2"></path>
                                    </svg>
                                </div>
                                <div class="vem-callout-content">
                                    <p class="vem-callout-title">{{ __("Didn't receive the email?") }}</p>
                                    <p class="vem-callout-text">{{ __('Check your spam folder or try resending the verification link.') }}</p>
                                </div>
                            @endif
                        </div>

                        @if (!$user || !$user->hasVerifiedEmail())
                            <form method="POST" action="{{ route('verification.send') }}" id="vemResendForm" class="vem-form">
                                @csrf
                                <button type="submit" class="vem-btn-primary" id="vemResendBtn">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" id="vemResendIcon" aria-hidden="true">
                                        <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"></path>
                                        <path d="M21 3v5h-5"></path>
                                        <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"></path>
                                        <path d="M8 16H3v5"></path>
                                    </svg>
                                    <span id="vemResendBtnText">{{ __('Resend Verification Email') }}</span>
                                    <span id="vemResendSpinner" class="vem-spinner" style="display: none;" aria-hidden="true"></span>
                                </button>
                            </form>
                        @endif

                        @if ($user)
                            <form method="POST" action="{{ route('logout') }}" class="vem-form">
                                @csrf
                                <button type="submit" class="vem-btn-secondary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                        <polyline points="16 17 21 12 16 7"></polyline>
                                        <line x1="21" y1="12" x2="9" y2="12"></line>
                                    </svg>
                                    <span>{{ __('Log Out') }}</span>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    {{-- OVERLAY MODAL MODE: Injected into pages, hidden by default, opens dynamically --}}
    <div id="verifyEmailModal" class="vem-overlay" role="dialog" aria-modal="true" aria-labelledby="verifyEmailModalTitle" style="{{ $shouldShow ? 'display: flex;' : 'display: none;' }}">
        <!-- Blurred Backdrop -->
        <div class="vem-backdrop" id="vemBackdrop" onclick="closeVerifyEmailModal()"></div>

        <!-- Modal Dialog -->
        <div class="vem-dialog" role="document">
            <div class="vem-card">
                <!-- Close button -->
                <button type="button" class="vem-close-btn" onclick="closeVerifyEmailModal()" aria-label="{{ __('Fechar') }}" title="{{ __('Fechar') }}">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>

                <!-- Card Header -->
                <div class="vem-card-header">
                    <h2 id="verifyEmailModalTitle" class="vem-title">{{ __('Verify your email address') }}</h2>
                    <p class="vem-subtitle">
                        @if ($user && $user->hasVerifiedEmail())
                            {{ __('O seu email já foi confirmado.') }}
                        @else
                            {{ __("We've sent a verification link to") }} <span class="vem-email-badge">{{ $user?->email ?? 'seu email' }}</span>. {{ __('Please check your inbox and click the link to verify your email address.') }}
                        @endif
                    </p>
                </div>

                <!-- Card Body -->
                <div class="vem-card-body">
                    <div class="vem-body-inner">
                        <!-- Circular Mail Icon -->
                        <div class="vem-icon-circle">
                            @if ($user && $user->hasVerifiedEmail())
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mail" aria-hidden="true">
                                    <path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7"></path>
                                    <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                                </svg>
                            @endif
                        </div>

                        <div class="vem-content-stack">
                            @if (session('status') == 'verification-link-sent')
                                <div class="vem-alert-banner" role="alert">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                    </svg>
                                    <span>{{ __('A new verification link has been sent to the email address you provided during registration.') }}</span>
                                </div>
                            @endif

                            <div class="vem-callout">
                                @if ($user && $user->hasVerifiedEmail())
                                    <div class="vem-callout-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #059669;" aria-hidden="true">
                                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                                            <polyline points="9 12 11 14 15 10"></polyline>
                                        </svg>
                                    </div>
                                    <div class="vem-callout-content">
                                        <p class="vem-callout-title">{{ __('Aguarde aprovação') }}</p>
                                        <p class="vem-callout-text">{{ __('O seu email já foi confirmado. Aguarde a aprovação do administrador para aceder ao dashboard.') }}</p>
                                    </div>
                                @else
                                    <div class="vem-callout-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <path d="M12 6v6l4 2"></path>
                                        </svg>
                                    </div>
                                    <div class="vem-callout-content">
                                        <p class="vem-callout-title">{{ __("Didn't receive the email?") }}</p>
                                        <p class="vem-callout-text">{{ __('Check your spam folder or try resending the verification link.') }}</p>
                                    </div>
                                @endif
                            </div>

                            @if (!$user || !$user->hasVerifiedEmail())
                                <form method="POST" action="{{ route('verification.send') }}" id="vemResendForm" class="vem-form">
                                    @csrf
                                    <button type="submit" class="vem-btn-primary" id="vemResendBtn">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" id="vemResendIcon" aria-hidden="true">
                                            <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"></path>
                                            <path d="M21 3v5h-5"></path>
                                            <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"></path>
                                            <path d="M8 16H3v5"></path>
                                        </svg>
                                        <span id="vemResendBtnText">{{ __('Resend Verification Email') }}</span>
                                        <span id="vemResendSpinner" class="vem-spinner" style="display: none;" aria-hidden="true"></span>
                                    </button>
                                </form>
                            @endif

                            @if ($user)
                                <form method="POST" action="{{ route('logout') }}" class="vem-form">
                                    @csrf
                                    <button type="submit" class="vem-btn-secondary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                            <polyline points="16 17 21 12 16 7"></polyline>
                                            <line x1="21" y1="12" x2="9" y2="12"></line>
                                        </svg>
                                        <span>{{ __('Log Out') }}</span>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

<style>
    @import url('https://fonts.googleapis.com/css2?family=Manrope:wght@500;600;700&display=swap');

    /* Standalone page shell */
    .vem-page-shell {
        min-height: calc(100vh - 140px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 16px;
        box-sizing: border-box;
        background: radial-gradient(circle at 10% 15%, #d8f0e1 0, #eef2f7 28%, #f6f7fb 60%);
        font-family: 'Manrope', 'Segoe UI', sans-serif;
    }

    /* Fixed overlay mode */
    .vem-overlay {
        position: fixed;
        inset: 0;
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        box-sizing: border-box;
        font-family: 'Manrope', 'Segoe UI', sans-serif;
    }

    .vem-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.65);
        animation: vem-fade-in 0.25s ease-out forwards;
    }

    .vem-dialog {
        position: relative;
        z-index: 2;
        width: 100%;
        max-width: 440px;
        margin: auto;
        animation: vem-slide-up 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        box-sizing: border-box;
    }

    .vem-card {
        position: relative;
        width: 100%;
        max-width: 440px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        box-shadow: 0 24px 60px -15px rgba(15, 23, 42, 0.2), 0 10px 25px -5px rgba(15, 23, 42, 0.08);
        color: #0f172a;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        box-sizing: border-box;
        margin: auto;
    }

    .vem-close-btn {
        position: absolute;
        top: 18px;
        right: 18px;
        z-index: 10;
        background: rgba(241, 245, 249, 0.85);
        border: 1px solid rgba(203, 213, 225, 0.6);
        color: #475569;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.15s ease;
        padding: 0;
    }

    .vem-close-btn:hover {
        background: #e2e8f0;
        color: #0f172a;
        transform: scale(1.06);
    }

    .vem-card-header {
        padding: 28px 28px 16px 28px;
        display: flex;
        flex-direction: column;
        gap: 8px;
        text-align: center;
        box-sizing: border-box;
    }

    .vem-title {
        font-size: 1.35rem;
        font-weight: 700;
        line-height: 1.25;
        letter-spacing: -0.015em;
        color: #0f172a;
        margin: 0;
    }

    .vem-subtitle {
        font-size: 0.875rem;
        color: #64748b;
        line-height: 1.55;
        margin: 0;
        word-break: normal;
        overflow-wrap: break-word;
    }

    .vem-email-badge {
        font-weight: 600;
        color: #0f172a;
        word-break: normal;
        overflow-wrap: break-word;
        display: inline;
    }

    .vem-card-body {
        padding: 0 28px 28px 28px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 20px;
        box-sizing: border-box;
        width: 100%;
    }

    .vem-body-inner {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 18px;
        width: 100%;
        box-sizing: border-box;
        text-align: center;
    }

    .vem-icon-circle {
        width: 60px;
        height: 60px;
        min-width: 60px;
        min-height: 60px;
        border-radius: 50%;
        background: #ecfdf5;
        border: 1px solid #d1fae5;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #2e7a3f;
        box-shadow: 0 4px 12px rgba(46, 122, 63, 0.12);
        margin: 0 auto;
        flex-shrink: 0;
    }

    .vem-icon-circle svg {
        display: block;
    }

    .vem-content-stack {
        width: 100%;
        display: flex;
        flex-direction: column;
        gap: 14px;
        box-sizing: border-box;
    }

    .vem-alert-banner {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 12px 14px;
        border-radius: 10px;
        font-size: 0.8125rem;
        line-height: 1.45;
        background-color: #ecfdf5;
        border: 1px solid #a7f3d0;
        color: #065f46;
        animation: vem-fade-in 0.25s ease-out;
        text-align: left;
        box-sizing: border-box;
    }

    .vem-alert-banner svg {
        width: 18px;
        height: 18px;
        min-width: 18px;
        flex-shrink: 0;
        margin-top: 1px;
        color: #059669;
    }

    .vem-callout {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        padding: 14px 16px;
        text-align: left;
        width: 100%;
        box-sizing: border-box;
    }

    .vem-callout-icon {
        width: 18px;
        height: 18px;
        min-width: 18px;
        max-width: 18px;
        color: #64748b;
        margin-top: 2px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .vem-callout-content {
        display: flex;
        flex-direction: column;
        gap: 3px;
        flex: 1;
        text-align: left;
    }

    .vem-callout-title {
        font-size: 0.875rem;
        font-weight: 600;
        color: #0f172a;
        margin: 0;
        line-height: 1.35;
    }

    .vem-callout-text {
        font-size: 0.8125rem;
        color: #64748b;
        line-height: 1.45;
        margin: 0;
    }

    .vem-form {
        width: 100%;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .vem-btn-primary {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        min-height: 44px;
        padding: 10px 16px;
        border-radius: 10px;
        font-size: 0.875rem;
        font-weight: 600;
        letter-spacing: 0.2px;
        color: #ffffff;
        background: linear-gradient(115deg, #2e7a3f, #1f572d);
        border: none;
        cursor: pointer;
        box-shadow: 0 4px 14px rgba(31, 87, 45, 0.25);
        transition: all 0.15s ease;
        text-decoration: none;
        box-sizing: border-box;
    }

    .vem-btn-primary:hover {
        opacity: 0.94;
        box-shadow: 0 6px 18px rgba(31, 87, 45, 0.32);
        transform: translateY(-1px);
        color: #ffffff;
    }

    .vem-btn-primary:active {
        transform: translateY(0);
    }

    .vem-btn-primary:disabled {
        opacity: 0.65;
        cursor: not-allowed;
        transform: none;
    }

    .vem-btn-secondary {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        min-height: 40px;
        padding: 8px 16px;
        border-radius: 10px;
        font-size: 0.875rem;
        font-weight: 500;
        color: #475569;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        cursor: pointer;
        transition: all 0.15s ease;
        text-decoration: none;
        box-sizing: border-box;
    }

    .vem-btn-secondary:hover {
        background: #f8fafc;
        color: #0f172a;
        border-color: #cbd5e1;
    }

    .vem-spinner {
        width: 16px;
        height: 16px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-top-color: #ffffff;
        border-radius: 50%;
        animation: vem-spin 0.7s linear infinite;
    }

    .vem-spinning {
        animation: vem-spin 0.9s linear infinite;
    }

    @keyframes vem-fade-in {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes vem-slide-up {
        from { opacity: 0; transform: translateY(18px) scale(0.97); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    @keyframes vem-spin {
        to { transform: rotate(360deg); }
    }
</style>

<script>
    window.openVerifyEmailModal = function() {
        const modal = document.getElementById('verifyEmailModal');
        if (modal) {
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
    };

    window.closeVerifyEmailModal = function() {
        const modal = document.getElementById('verifyEmailModal');
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }
    };

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('verifyEmailModal');
            if (modal && modal.style.display === 'flex') {
                closeVerifyEmailModal();
            }
        }
    });

    (function() {
        const form = document.getElementById('vemResendForm');
        const btn = document.getElementById('vemResendBtn');
        const text = document.getElementById('vemResendBtnText');
        const spinner = document.getElementById('vemResendSpinner');
        const icon = document.getElementById('vemResendIcon');

        form?.addEventListener('submit', function() {
            if (btn) {
                btn.disabled = true;
                if (icon) icon.classList.add('vem-spinning');
                if (spinner) spinner.style.display = 'inline-block';
                if (text) text.textContent = '{{ __("A enviar...") }}';
            }
        });
    })();
</script>
