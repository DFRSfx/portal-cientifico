<x-app-layout>

    <x-slot:title>
        {{ __('Perfil') }}
    </x-slot>

    <section style="background-color: #eee;">
        <div class="container py-3">
            <div class="row">

                <!-- Sync notice banner/modal -->
                <div x-data="{ showNotice: false }"
                     @open-sync-notice.window="showNotice = true"
                     x-show="showNotice"
                     style="display: none;"
                     class="fixed inset-x-0 top-4 z-50 flex justify-center px-4"
                     id="exampleFrameModal2">
                    <div class="bg-emerald-900 text-white px-5 py-3 rounded-xl shadow-xl flex items-center gap-3 max-w-lg border border-emerald-700">
                        <i class="fas fa-info-circle text-emerald-400"></i>
                        <p class="mb-0 text-sm font-medium flex-1">
                            {{ __('Deve sincronizar os seus dados com o CienciaVitae!') }}
                        </p>
                        <button type="button" @click="showNotice = false" class="text-white/70 hover:text-white text-base font-bold px-2 py-1 cursor-pointer bg-transparent border-0 leading-none">
                            &times;
                        </button>
                    </div>
                </div>

                <div class="col-xl-3">
                    <div class="card mb-4 text-center">
                        <div class="bg-gradient-to-r from-emerald-800 to-emerald-700 h-20"></div>
                        <div class="mx-auto relative -mt-10 w-20 h-20 rounded-full border-4 border-white shadow-md overflow-hidden bg-white">
                            <x-user-image showLogedUserImage="{{ auth()->user()->type != 'administrative' }}"
                                class="w-full h-full object-cover" height="36" />
                        </div>

                        <div class="card-body p-4 text-center">
                            <h5 class="font-bold text-slate-900 mb-1">{{ $user->name }}</h5>
                            <p class="text-xs text-slate-500 uppercase font-semibold mb-0">{{ $user->type }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-xl-9">
                    <div class="card mb-2">
                        <div class="card-body p-5">

                            <!-- Alpine Tabs -->
                            <div x-data="{ activeTab: '{{ $errors->updatePassword->any() ? 'settings' : 'profile' }}' }">
                                <div class="border-b border-slate-200 mb-6 flex gap-2" role="tablist">
                                    <button type="button"
                                        @click="activeTab = 'profile'"
                                        :class="activeTab === 'profile' ? 'border-emerald-700 text-emerald-800 font-semibold border-b-2' : 'border-transparent text-slate-500 hover:text-slate-700'"
                                        class="px-4 py-2.5 text-sm font-medium transition cursor-pointer bg-transparent border-0"
                                        role="tab"
                                        :aria-selected="activeTab === 'profile'">
                                        {{ __('Informação do Perfil') }}
                                    </button>
                                    <button type="button"
                                        @click="activeTab = 'settings'"
                                        :class="activeTab === 'settings' ? 'border-emerald-700 text-emerald-800 font-semibold border-b-2' : 'border-transparent text-slate-500 hover:text-slate-700'"
                                        class="px-4 py-2.5 text-sm font-medium transition cursor-pointer bg-transparent border-0"
                                        role="tab"
                                        :aria-selected="activeTab === 'settings'">
                                        {{ __('Segurança') }}
                                    </button>
                                </div>

                                <!-- Tabs content -->
                                <div>
                                    <div x-show="activeTab === 'profile'" role="tabpanel">
                                        @include('profile.partials.update-profile-information-form')
                                    </div>
                                    <div x-show="activeTab === 'settings'" style="display: none;" role="tabpanel">
                                        <div class="col p-2">@include('profile.partials.update-password-form')</div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
    </section>

    @if (session('status') === 'profile-updated' || session('status') === 'password-updated')
        <x-alert-component id="alert-message" error-message="0" message="Guardado" />
    @elseif($errors->updatePassword->any() || $errors->any())
        <x-alert-component id="alert-message" error-message="1" message="Erro" />
    @endif


</x-app-layout>
