<x-guest-layout>
    <x-slot:title>
        {{ __('Repor Palavra-passe') }}
    </x-slot>

    <div class="min-h-[calc(100vh-160px)] flex items-center justify-center py-12 px-4 sm:px-6">
        <div class="w-full max-w-md">
            {{-- Brand / Header --}}
            <div class="text-center mb-8">
                <a href="{{ route('home') }}" class="inline-block mb-3">
                    <img src="{{ asset('logo/logo-portal-cientifico.svg') }}" alt="Logo Portal Científico" class="h-10 mx-auto w-auto">
                </a>
                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                    {{ __('Repor Palavra-passe') }}
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    {{ __('Introduza a sua nova palavra-passe para aceder à sua conta.') }}
                </p>
            </div>

            {{-- Card --}}
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 sm:p-8 shadow-sm">
                <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
                    @csrf

                    <!-- Password Reset Token -->
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            {{ __('Email') }}
                        </label>
                        <input type="email" id="email" name="email"
                            class="w-full px-3.5 py-2.5 text-sm bg-white border @error('email') border-rose-300 ring-1 ring-rose-300 @else border-slate-200 @enderror rounded-xl shadow-2xs focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all placeholder:text-slate-400"
                            value="{{ old('email', $request->email) }}" required autofocus autocomplete="username">
                        @error('email')
                            <p class="text-xs text-rose-600 mt-1.5 flex items-center gap-1">
                                <i class="fas fa-circle-exclamation text-[10px]"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            {{ __('Nova Palavra-passe') }}
                        </label>
                        <input type="password" id="password" name="password"
                            class="w-full px-3.5 py-2.5 text-sm bg-white border @error('password') border-rose-300 ring-1 ring-rose-300 @else border-slate-200 @enderror rounded-xl shadow-2xs focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all placeholder:text-slate-400"
                            required autocomplete="new-password">
                        @error('password')
                            <p class="text-xs text-rose-600 mt-1.5 flex items-center gap-1">
                                <i class="fas fa-circle-exclamation text-[10px]"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            {{ __('Confirmar Nova Palavra-passe') }}
                        </label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            class="w-full px-3.5 py-2.5 text-sm bg-white border @error('password_confirmation') border-rose-300 ring-1 ring-rose-300 @else border-slate-200 @enderror rounded-xl shadow-2xs focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all placeholder:text-slate-400"
                            required autocomplete="new-password">
                        @error('password_confirmation')
                            <p class="text-xs text-rose-600 mt-1.5 flex items-center gap-1">
                                <i class="fas fa-circle-exclamation text-[10px]"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 px-5 py-2.5 text-xs sm:text-sm font-semibold text-white bg-emerald-700 hover:bg-emerald-800 rounded-xl shadow-xs transition-all cursor-pointer">
                            <i class="fas fa-key text-xs"></i>
                            <span>{{ __('Atualizar Palavra-passe') }}</span>
                        </button>
                    </div>

                    <div class="text-center pt-2">
                        <a href="{{ route('home') }}" class="text-xs text-slate-500 hover:text-emerald-800 transition-colors">
                            {{ __('Voltar à página inicial') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
