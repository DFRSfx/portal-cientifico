<section>
    <div class="mb-6">
        <h3 class="text-base sm:text-lg font-semibold text-slate-900 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-emerald-700">
                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
            </svg>
            <span>{{ __('Informação Pessoal') }}</span>
        </h3>
        <p class="mt-1 text-xs sm:text-sm text-slate-500">
            {{ __('Atualize o seu nome completo e o endereço de email associado à sua conta.') }}
        </p>
    </div>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
        @csrf
        @method('patch')

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
            <div>
                <x-form.mdb-outline-input label="1" labelValue="{{ __('Nome') }}" type="text" name="name"
                    value="{{ old('name', $user->name) }}" divContainerExtraClass="mb-0" />
            </div>

            <div>
                <x-form.mdb-outline-input label="1" labelValue="{{ __('Email') }}" type="email" name="email"
                    value="{{ old('email', $user->email) }}" divContainerExtraClass="mb-0" />
            </div>
        </div>

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
            <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 flex items-start gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-amber-600 mt-0.5 shrink-0">
                    <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"></path>
                    <line x1="12" y1="9" x2="12" y2="13"></line>
                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                </svg>
                <div class="text-xs sm:text-sm">
                    <p class="font-semibold text-amber-900">
                        {{ __('O seu endereço de email ainda não foi verificado.') }}
                    </p>
                    <button form="send-verification" type="submit"
                        class="mt-1.5 inline-flex items-center gap-1 font-semibold text-emerald-800 hover:text-emerald-900 underline cursor-pointer bg-transparent border-0 p-0 text-xs sm:text-sm transition-colors">
                        {{ __('Clique aqui para reenviar o email de verificação.') }}
                    </button>
                </div>
            </div>

            @if (session('status') === 'verification-link-sent')
                <div class="p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm font-medium flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-emerald-600 shrink-0">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    <span>{{ __('Foi enviado um novo link de verificação para o seu email.') }}</span>
                </div>
            @endif
        @endif

        <div class="pt-4 flex items-center justify-end border-t border-slate-100">
            <button type="submit" class="h-10 inline-flex items-center gap-2 px-5 bg-emerald-700 hover:bg-emerald-800 active:bg-emerald-900 text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-700/30 transition-all font-semibold text-xs sm:text-sm shadow-xs hover:shadow cursor-pointer border-0">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                <span>{{ __('Guardar Alterações') }}</span>
            </button>
        </div>
    </form>
</section>
