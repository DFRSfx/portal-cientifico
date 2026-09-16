<x-app-layout>

    <x-slot:title>
        {{ __('O Meu Perfil') }}
    </x-slot>

    {{-- Sync notice banner --}}
    <div x-data="{ showNotice: false }"
         @open-sync-notice.window="showNotice = true"
         x-show="showNotice"
         x-cloak
         class="fixed inset-x-0 top-4 z-50 flex justify-center px-4"
         id="exampleFrameModal2">
        <div class="bg-emerald-900 text-white px-5 py-3.5 rounded-2xl shadow-xl flex items-center gap-3 max-w-lg border border-emerald-700">
            <i class="fas fa-info-circle text-emerald-400 text-base"></i>
            <p class="mb-0 text-xs sm:text-sm font-medium flex-1">
                {{ __('Deve sincronizar os seus dados com o CienciaVitae!') }}
            </p>
            <button type="button" @click="showNotice = false" class="text-white/70 hover:text-white text-lg font-bold px-2 py-1 cursor-pointer bg-transparent border-0 leading-none" aria-label="Fechar">
                &times;
            </button>
        </div>
    </div>

    <div class="bg-slate-50/50 min-h-[calc(100vh-140px)] py-6 sm:py-10">
        <div class="w-full max-w-[1440px] mx-auto px-4 sm:px-6">

            {{-- Header da Página --}}
            <div class="mb-6 sm:mb-8">
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">{{ __('O Meu Perfil') }}</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">{{ __('Faça a gestão das suas informações pessoais e credenciais de acesso.') }}</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start">

                {{-- Coluna Esquerda: Cartão de Perfil do Utilizador --}}
                <div class="lg:col-span-4 xl:col-span-4">
                    <div class="card rounded-2xl border border-slate-200/80 bg-white shadow-sm overflow-hidden text-center">
                        <div class="bg-gradient-to-br from-emerald-800 via-emerald-700 to-emerald-900 h-24 relative">
                            <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:14px_14px]"></div>
                        </div>

                        <div class="px-5 pb-6">
                            <div class="mx-auto relative -mt-12 w-24 h-24 rounded-full border-4 border-white shadow-md overflow-hidden bg-white ring-2 ring-emerald-700/10 flex items-center justify-center">
                                <x-user-image showLogedUserImage="{{ auth()->user()->type != 'administrative' }}"
                                    class="w-full h-full object-cover block" height="88" width="88" />
                            </div>

                            <h2 class="font-bold text-slate-900 text-lg mt-3 mb-1 truncate" title="{{ $user->name }}">
                                {{ $user->name }}
                            </h2>

                            <div class="flex items-center justify-center gap-1.5 mb-4">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200/80 uppercase tracking-wider">
                                    <i class="fas fa-user-tag text-[10px]"></i>
                                    <span>{{ $user->type }}</span>
                                </span>
                            </div>

                            {{-- Detalhes Meta --}}
                            <div class="border-t border-slate-100 pt-3.5 text-left space-y-2.5">
                                {{-- Email --}}
                                <div class="flex items-center justify-between gap-2 text-xs sm:text-sm text-slate-600">
                                    <div class="flex items-center gap-2 truncate min-w-0">
                                        <i class="fas fa-envelope text-slate-400 w-4 shrink-0 text-center"></i>
                                        <span class="truncate text-slate-700 font-medium" title="{{ $user->email }}">{{ $user->email }}</span>
                                    </div>
                                    @if ($user->hasVerifiedEmail())
                                        <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200/60 shrink-0">
                                            <i class="fas fa-check-circle"></i>
                                            <span>{{ __('Verificado') }}</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-md border border-amber-200/60 shrink-0">
                                            <i class="fas fa-exclamation-circle"></i>
                                            <span>{{ __('Pendente') }}</span>
                                        </span>
                                    @endif
                                </div>

                                {{-- Ciência Vitae ID & SSO Linking --}}
                                <div class="pt-2.5 border-t border-slate-100 space-y-2">
                                    <div class="flex items-center justify-between gap-2 text-xs sm:text-sm text-slate-600">
                                        <div class="flex items-center gap-2 shrink-0">
                                            <i class="fas fa-id-card text-slate-400 w-4 text-center"></i>
                                            <span class="text-slate-500 font-medium">{{ __('Ciência ID') }}</span>
                                        </div>
                                        @if (!empty($user->ciencia_vitae))
                                            <span class="font-mono text-xs font-semibold text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200 truncate inline-flex items-center gap-1">
                                                <i class="fas fa-circle-check text-[10px] text-emerald-600"></i>
                                                {{ $user->ciencia_vitae }}
                                            </span>
                                        @else
                                            <span class="text-xs text-slate-400 italic">
                                                {{ __('Não associado') }}
                                            </span>
                                        @endif
                                    </div>

                                    @if (empty($user->ciencia_vitae))
                                        <div class="pt-1">
                                            <a href="{{ route('auth.ciencia-vitae.redirect') }}"
                                               class="w-full inline-flex items-center justify-center gap-2 px-3 py-2 text-xs font-semibold text-emerald-800 bg-emerald-50 hover:bg-emerald-100 active:bg-emerald-200 rounded-xl border border-emerald-300 transition-all shadow-2xs">
                                                <img src="{{ asset('logo/cienciaVitae.svg') }}" class="w-4 h-4 object-contain" alt="Ciência Vitae" onerror="this.src='{{ asset('logo/icon-portalcientifico.svg') }}'">
                                                <span>{{ __('Associar Conta Ciência Vitae') }}</span>
                                            </a>
                                        </div>
                                    @else
                                        <div class="pt-0.5 flex justify-end">
                                            <form method="POST" action="{{ route('auth.ciencia-vitae.unlink') }}" onsubmit="return confirm('{{ __('Deseja desassociar o Ciência ID desta conta?') }}');">
                                                @csrf
                                                <button type="submit" class="text-[11px] text-slate-400 hover:text-rose-600 transition-colors cursor-pointer bg-transparent border-0 p-0 underline">
                                                    {{ __('Desassociar Ciência ID') }}
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>

                                {{-- Entidade --}}
                                @if (!empty($user->entidade))
                                    <div class="flex items-center justify-between gap-2 text-xs sm:text-sm text-slate-600 pt-2 border-t border-slate-100">
                                        <div class="flex items-center gap-2 shrink-0">
                                            <i class="fas fa-building text-slate-400 w-4 text-center"></i>
                                            <span class="text-slate-500 font-medium">{{ __('Entidade') }}</span>
                                        </div>
                                        <span class="text-xs font-semibold text-slate-700 truncate max-w-[170px]" title="{{ $user->entidade }}">
                                            {{ $user->entidade }}
                                        </span>
                                    </div>
                                @endif

                                {{-- Ver Perfil Público se for Autor --}}
                                @if (isset($user->authorInformation) && !empty($user->authorInformation->id))
                                    <div class="pt-3 border-t border-slate-100">
                                        <a href="{{ route('authors.show', $user->authorInformation->id) }}"
                                           class="w-full inline-flex items-center justify-center gap-2 px-3 py-2 text-xs font-semibold text-emerald-800 bg-emerald-50 hover:bg-emerald-100 active:bg-emerald-200 rounded-xl border border-emerald-200/80 transition-all shadow-2xs">
                                            <i class="fas fa-arrow-up-right-from-square text-xs"></i>
                                            <span>{{ __('Ver Perfil de Autor') }}</span>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Coluna Direita: Cartão de Conteúdo (Abas) --}}
                <div class="lg:col-span-8 xl:col-span-8">
                    <div class="card rounded-2xl border border-slate-200/80 bg-white shadow-sm overflow-hidden"
                         x-data="{ activeTab: '{{ $errors->updatePassword->any() ? 'settings' : 'profile' }}' }">

                        {{-- Barra de Abas --}}
                        <div class="px-6 pt-4 pb-0 border-b border-slate-200/80 bg-slate-50/50">
                            <div class="flex items-center gap-4 sm:gap-6" role="tablist">
                                <button type="button"
                                    @click="activeTab = 'profile'"
                                    :class="activeTab === 'profile' ? 'border-emerald-700 text-emerald-800 font-semibold' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300'"
                                    class="flex items-center gap-2 pb-3.5 pt-1 px-1 border-b-2 text-xs sm:text-sm font-medium transition-all cursor-pointer bg-transparent border-0 border-b-2"
                                    role="tab"
                                    :aria-selected="activeTab === 'profile'">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="shrink-0" :class="activeTab === 'profile' ? 'text-emerald-700' : 'text-slate-400'">
                                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                    <span>{{ __('Informação Pessoal') }}</span>
                                </button>
                                <button type="button"
                                    @click="activeTab = 'settings'"
                                    :class="activeTab === 'settings' ? 'border-emerald-700 text-emerald-800 font-semibold' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300'"
                                    class="flex items-center gap-2 pb-3.5 pt-1 px-1 border-b-2 text-xs sm:text-sm font-medium transition-all cursor-pointer bg-transparent border-0 border-b-2"
                                    role="tab"
                                    :aria-selected="activeTab === 'settings'">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="shrink-0" :class="activeTab === 'settings' ? 'text-emerald-700' : 'text-slate-400'">
                                        <rect width="18" height="11" x="3" y="11" rx="2" ry="2"></rect>
                                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                    </svg>
                                    <span>{{ __('Segurança & Palavra-Passe') }}</span>
                                </button>
                            </div>
                        </div>

                        {{-- Conteúdo das Abas --}}
                        <div class="p-6 sm:p-8">
                            <div x-show="activeTab === 'profile'" role="tabpanel" x-transition.opacity.duration.150ms>
                                @include('profile.partials.update-profile-information-form')
                            </div>

                            <div x-show="activeTab === 'settings'" style="display: none;" role="tabpanel" x-transition.opacity.duration.150ms>
                                @include('profile.partials.update-password-form')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (session('status') === 'profile-updated' || session('status') === 'password-updated')
        <x-alert-component id="alert-message" error-message="0" message="Guardado com sucesso" />
    @elseif($errors->updatePassword->any() || $errors->any())
        <x-alert-component id="alert-message" error-message="1" message="Ocorreu um erro ao atualizar os dados" />
    @endif

</x-app-layout>
