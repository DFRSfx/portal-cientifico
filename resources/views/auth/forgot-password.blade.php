<x-guest-layout>
    @php
        $errors = $errors ?? session('errors', new \Illuminate\Support\ViewErrorBag());
    @endphp

    <x-slot:title>
        {{ __('Recuperar Palavra-passe') }}
    </x-slot>

    <div class="min-h-[calc(100vh-160px)] flex items-center justify-center py-12 px-4 sm:px-6">
        <div class="w-full max-w-md">
            {{-- Brand / Header --}}
            <div class="text-center mb-8">
                <a href="{{ route('home') }}" class="inline-block mb-3">
                    <img src="{{ asset('logo/logo-portal-cientifico.svg') }}" alt="Logo Portal Científico" class="h-10 mx-auto w-auto">
                </a>
                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                    {{ __('Recuperar Palavra-passe') }}
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    {{ __('Introduza o seu email para receber uma ligação de reposição.') }}
                </p>
            </div>

            {{-- Alerts --}}
            @if (session('status'))
                <div class="mb-4 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs font-medium flex items-center gap-2.5 shadow-2xs">
                    <i class="fas fa-check-circle text-emerald-600 text-sm"></i>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            @if ($errors->has('email'))
                <div class="mb-4 p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-900 text-xs font-medium flex items-center gap-2.5 shadow-2xs">
                    <i class="fas fa-triangle-exclamation text-rose-600 text-sm"></i>
                    <span>{{ $errors->first('email') }}</span>
                </div>
            @endif

            {{-- Card --}}
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 sm:p-8 shadow-sm">
                <form method="POST" action="{{ route('password.email') }}" class="space-y-4" id="forgotPasswordPageForm">
                    @csrf

                    <div>
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            {{ __('Email Institucional') }}
                        </label>
                        <input type="email" id="email" name="email"
                            class="w-full px-3.5 py-2.5 text-sm bg-white border {{ $errors->has('email') ? 'border-rose-300 ring-1 ring-rose-300' : 'border-slate-200' }} rounded-xl shadow-2xs focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all placeholder:text-slate-400"
                            placeholder="utilizador@islagaia.pt"
                            value="{{ old('email') }}"
                            required autocomplete="email" autofocus>
                    </div>

                    <div class="pt-2">
                        <button type="submit" id="fpPageSubmitBtn"
                            class="w-full inline-flex items-center justify-center gap-2 px-5 py-2.5 text-xs sm:text-sm font-semibold text-white bg-emerald-700 hover:bg-emerald-800 rounded-xl shadow-xs transition-all cursor-pointer">
                            <span id="fpPageSubmitText">{{ __('Enviar Ligação de Reposição') }}</span>
                            <span id="fpPageSubmitSpinner" class="hidden"><i class="fas fa-spinner fa-spin text-xs"></i></span>
                        </button>
                    </div>

                    <div class="text-center pt-3 border-t border-slate-100">
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-600 hover:text-emerald-800 transition-colors">
                            <i class="fas fa-arrow-left text-[10px]"></i>
                            <span>{{ __('Voltar ao início de sessão') }}</span>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('forgotPasswordPageForm');
            const btn = document.getElementById('fpPageSubmitBtn');
            const spinner = document.getElementById('fpPageSubmitSpinner');
            const text = document.getElementById('fpPageSubmitText');

            form?.addEventListener('submit', function() {
                if (btn) {
                    btn.disabled = true;
                    if (spinner) spinner.classList.remove('hidden');
                    if (text) text.textContent = '{{ __("A enviar...") }}';
                }
            });
        });
    </script>
</x-guest-layout>
