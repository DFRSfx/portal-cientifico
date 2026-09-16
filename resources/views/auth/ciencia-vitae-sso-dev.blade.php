<x-guest-layout>
    <x-slot:title>
        {{ __('Autenticação Ciência Vitae (SSO)') }}
    </x-slot>

    <div class="min-h-[calc(100vh-140px)] flex items-center justify-center py-10 px-4 sm:px-6">
        <div class="max-w-xl w-full bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden">
            
            {{-- Header with Ciência Vitae Branding --}}
            <div class="bg-gradient-to-r from-emerald-800 to-teal-800 p-6 sm:p-8 text-white text-center relative overflow-hidden">
                <div class="relative z-1 space-y-2">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/15 border border-white/20 text-xs font-semibold tracking-wider uppercase mb-1">
                        <i class="fas fa-shield-halved text-emerald-300"></i>
                        <span>{{ __('Serviço de Autenticação Central FCT') }}</span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-bold tracking-tight m-0">
                        {{ __('Autenticação Ciência ID') }}
                    </h1>
                    <p class="text-xs sm:text-sm text-emerald-100 max-w-md mx-auto m-0 leading-relaxed">
                        {{ $isLinking ? __('Associe o seu perfil oficial Ciência Vitae à sua conta no Portal Científico.') : __('Inicie sessão no Portal Científico utilizando a sua identidade digital Ciência Vitae.') }}
                    </p>
                </div>
            </div>

            <div class="p-6 sm:p-8 space-y-6">

                {{-- Dev Environment Notice --}}
                <div class="p-4 rounded-xl bg-amber-50 border border-amber-200/80 text-xs text-amber-900 flex items-start gap-3">
                    <i class="fas fa-flask text-amber-600 text-sm mt-0.5 shrink-0"></i>
                    <div class="space-y-1">
                        <p class="font-bold m-0">{{ __('Ambiente de Desenvolvimento / Sandbox SSO') }}</p>
                        <p class="text-amber-800 m-0 leading-relaxed">
                            {{ __('Este ecrã simula o fluxo oficial do OAuth 2.0 da FCT. Pode selecionar um investigador existente do ISLA para testar a associação imediata, ou introduzir um Ciência ID novo para testar a criação automática da conta.') }}
                        </p>
                    </div>
                </div>

                {{-- Option 1: Fast-Select Known Institution Researcher --}}
                @if(isset($sampleResearchers) && $sampleResearchers->count() > 0)
                    <div class="space-y-3">
                        <label class="text-xs font-bold uppercase tracking-wider text-slate-500 block">
                            {{ __('Opção 1: Selecionar Investigador Existente para Autenticar') }}
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                            @foreach($sampleResearchers as $res)
                                <form method="GET" action="{{ route('auth.ciencia-vitae.callback') }}">
                                    <input type="hidden" name="state" value="{{ $state }}">
                                    <input type="hidden" name="ciencia_id" value="{{ $res->ciencia_vitae }}">
                                    <input type="hidden" name="name" value="{{ $res->name }}">
                                    <input type="hidden" name="email" value="{{ $res->email }}">
                                    <input type="hidden" name="affiliation" value="{{ $res->entidade ?? 'ISLA' }}">

                                    <button type="submit"
                                            class="w-full text-left p-3 rounded-xl border border-slate-200 hover:border-emerald-500 hover:bg-emerald-50/50 transition-all group flex items-center justify-between gap-2 cursor-pointer shadow-2xs">
                                        <div class="min-w-0">
                                            <span class="text-xs font-bold text-slate-900 group-hover:text-emerald-800 block truncate">
                                                {{ $res->name }}
                                            </span>
                                            <span class="text-[11px] font-mono text-slate-500 block">
                                                {{ $res->ciencia_vitae }}
                                            </span>
                                        </div>
                                        <i class="fas fa-arrow-right text-xs text-slate-400 group-hover:text-emerald-700 transition-transform group-hover:translate-x-0.5"></i>
                                    </button>
                                </form>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Option 2: Custom / New Researcher Authentication --}}
                <div class="space-y-3 pt-4 border-t border-slate-100">
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-500 block">
                        {{ __('Opção 2: Introduzir Manualmente um Ciência ID (Novo ou Externo)') }}
                    </label>

                    <form method="GET" action="{{ route('auth.ciencia-vitae.callback') }}" class="space-y-3">
                        <input type="hidden" name="state" value="{{ $state }}">

                        <div class="space-y-1">
                            <label for="input_ciencia_id" class="text-xs font-semibold text-slate-700">{{ __('Ciência ID') }} *</label>
                            <input type="text" id="input_ciencia_id" name="ciencia_id" required
                                   placeholder="Ex.: B61C-DDF6-1A69"
                                   pattern="^([A-Za-z0-9]{4}-){2}[A-Za-z0-9]{4}$"
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-mono focus:ring-2 focus:ring-emerald-500 focus:outline-none uppercase">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div class="space-y-1">
                                <label for="input_name" class="text-xs font-semibold text-slate-700">{{ __('Nome Completo') }}</label>
                                <input type="text" id="input_name" name="name"
                                       placeholder="Ex.: Dra. Maria Santos"
                                       class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                            </div>
                            <div class="space-y-1">
                                <label for="input_email" class="text-xs font-semibold text-slate-700">{{ __('Email Institucional') }}</label>
                                <input type="email" id="input_email" name="email"
                                       placeholder="msantos@islagaia.pt"
                                       class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                            </div>
                        </div>

                        <button type="submit"
                                class="w-full mt-2 py-2.5 px-4 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-semibold text-xs sm:text-sm shadow-xs transition-colors flex items-center justify-center gap-2 cursor-pointer">
                            <i class="fas fa-check"></i>
                            <span>{{ __('Confirmar Autenticação e Entrar') }}</span>
                        </button>
                    </form>
                </div>

                <div class="text-center pt-2 space-y-2">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-lg bg-slate-100 border border-slate-200/80 text-[11px] font-mono text-slate-600">
                        <i class="fas fa-key text-slate-400"></i>
                        <span>Client ID: <strong>{{ config('services.ciencia_vitae.client_id') }}</strong> (middleware.islagaia.pt)</span>
                    </div>
                    <div>
                        <a href="{{ url()->previous() ?: route('home') }}" class="text-xs text-slate-500 hover:text-slate-800 transition-colors">
                            &larr; {{ __('Cancelar e voltar') }}
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-guest-layout>
