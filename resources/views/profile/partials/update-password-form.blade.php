<section>
    <div class="mb-6">
        <h3 class="text-base sm:text-lg font-semibold text-slate-900 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-emerald-700">
                <rect width="18" height="11" x="3" y="11" rx="2" ry="2"></rect>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
            </svg>
            <span>{{ __('Alterar Palavra-Passe') }}</span>
        </h3>
        <p class="mt-1 text-xs sm:text-sm text-slate-500">
            {{ __('Certifique-se de que a sua conta utiliza uma palavra-passe forte e segura para proteger os seus acessos.') }}
        </p>
    </div>

    <form method="post" action="{{ route('password.update') }}" class="space-y-4">
        @csrf
        @method('put')

        <div>
            <x-form.mdb-outline-input label="1" bag="updatePassword" labelValue="{{ __('Palavra-Passe Atual') }}" type="password" name="current_password"
                value="" divContainerExtraClass="mb-0" />
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
            <div>
                <x-form.mdb-outline-input label="1" bag="updatePassword" labelValue="{{ __('Nova Palavra-Passe') }}" type="password" name="password"
                    value="" divContainerExtraClass="mb-0" />
            </div>

            <div>
                <x-form.mdb-outline-input label="1" bag="updatePassword" labelValue="{{ __('Confirmar Nova Palavra-Passe') }}" type="password"
                    name="password_confirmation" value="" divContainerExtraClass="mb-0" />
            </div>
        </div>

        <div class="pt-4 flex items-center justify-end border-t border-slate-100">
            <button type="submit" class="h-10 inline-flex items-center gap-2 px-5 bg-emerald-700 hover:bg-emerald-800 active:bg-emerald-900 text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-700/30 transition-all font-semibold text-xs sm:text-sm shadow-xs hover:shadow cursor-pointer border-0">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                <span>{{ __('Atualizar Palavra-Passe') }}</span>
            </button>
        </div>
    </form>
</section>
