<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Atualize a palavras-passe') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <x-form.mdb-outline-input label="1" bag="updatePassword" labelValue="{{ __('Palavra-Passe Atual') }}" type="password" name="current_password"
            value="" />

        <x-form.mdb-outline-input label="1" bag="updatePassword" labelValue="{{ __('Nova Palavra-Passe') }}" type="password" name="password"
            value="" />

        <x-form.mdb-outline-input label="1" bag="updatePassword" labelValue="{{ __('Confirmar palavra-passe') }}" type="password"
            name="password_confirmation" value="" />

        <div class="flex items-center gap-4">
            <button type="submit" class="btn btn-rounded gain-bg text-white ripple-surface-dark"
                data-mdb-ripple-color="dark" style="background-color: rgb(51, 100, 43);">
                {{ __('Guardar') }}
            </button>
        </div>
    </form>
</section>
