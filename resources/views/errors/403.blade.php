<x-app-layout>
    <x-slot:title>
        {{ __('403 - Acesso Não Autorizado') }}
    </x-slot>

    <div class="min-h-[calc(100vh-220px)] flex items-center justify-center py-12 px-4 sm:px-6">
        <div class="max-w-md w-full text-center">
            <div class="w-20 h-20 rounded-3xl bg-rose-50 border border-rose-200/80 text-rose-700 flex items-center justify-center mx-auto mb-6 shadow-sm">
                <i class="fas fa-shield-halved text-3xl"></i>
            </div>

            <h1 class="text-6xl sm:text-7xl font-extrabold text-slate-900 tracking-tight mb-2">403</h1>
            <h2 class="text-lg sm:text-xl font-bold text-slate-800 mb-3">{{ __('Acesso Não Autorizado') }}</h2>
            <p class="text-xs sm:text-sm text-slate-500 max-w-sm mx-auto mb-8 leading-relaxed">
                {{ __('Não tem permissão para aceder a este recurso. Se considera que se trata de um erro, contacte a administração do sistema.') }}
            </p>

            <div class="flex flex-wrap items-center justify-center gap-3">
                <a href="{{ route('home') }}"
                    class="inline-flex items-center gap-2 px-5 py-2.5 text-xs sm:text-sm font-semibold text-white bg-emerald-700 hover:bg-emerald-800 rounded-xl shadow-xs transition-all cursor-pointer">
                    <i class="fas fa-house text-xs"></i>
                    <span>{{ __('Página Inicial') }}</span>
                </a>
                <button type="button" onclick="history.back()"
                    class="inline-flex items-center gap-2 px-5 py-2.5 text-xs sm:text-sm font-semibold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 rounded-xl shadow-2xs transition-colors cursor-pointer">
                    <i class="fas fa-arrow-left text-xs"></i>
                    <span>{{ __('Voltar Atrás') }}</span>
                </button>
            </div>
        </div>
    </div>
</x-app-layout>