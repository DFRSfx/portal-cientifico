<x-app-layout>
    <link href="{{ asset('css\extra.css') }}" rel="stylesheet">

    <x-slot:title>
        {{ __('Perfil') }}
    </x-slot>

    <section style="background-color: #eee;">
        <div class="container py-3">
            <div class="row">

                <!-- Modal frame bottom example -->
                <div class="modal frame fade top " id="exampleFrameModal2" tabindex="-1"
                    aria-labelledby="exampleFrameModal2" aria-hidden="true">
                    <div class="modal-dialog modal-frame modal-top ">
                        <div class="modal-content rounded-0">
                            <div class="modal-body py-1">
                                <div class="d-flex justify-content-center align-items-center my-3">
                                    <p class="mb-0 text-bolder">
                                        {{ __('Deve sincronizar os seus dados com o CienciaVitae!') }}</p>
                                    <button type="button" class="btn btn-primary btn-sm ms-2"
                                        data-mdb-dismiss="modal">X</button>
                                </div>
                            </div>
                        </div>
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
                        <div class="card-body">

                            <!-- Tabs navs -->
                            <ul class="nav nav-tabs mb-3" id="ex1" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="{{ !$errors->updatePassword->any() ? 'nav-link active' : 'nav-link' }}" id="profile" data-mdb-toggle="tab" href="#profile-tab"
                                        role="tab" aria-controls="profile-tab" style="color:#2A6B20 ;"
                                        aria-selected="true">{{ __('Informação do Perfil') }}</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="{{ $errors->updatePassword->any() ? 'nav-link active' : 'nav-link' }}" id="settings" data-mdb-toggle="tab" href="#settings-tab"
                                        role="tab" aria-controls="settings-tab" style="color:#2A6B20 ;"
                                        aria-selected="false">{{ __('Segurança') }}</a>
                                </li>
                            </ul>
                            <!-- Tabs navs -->

                            <!-- Tabs content -->
                            <div class="tab-content" id="ex1-content">
                                <div class="tab-pane fade {{ !$errors->updatePassword->any() ? 'show active' : '' }} " id="profile-tab" role="tabpanel"
                                    aria-labelledby="ex1-tab-1">
                                    @include('profile.partials.update-profile-information-form')
                                </div>
                                <div class="tab-pane fade {{ $errors->updatePassword->any() ? 'show active' : '' }}" id="settings-tab" role="tabpanel"
                                    aria-labelledby="ex1-tab-2">
                                    <div class="col p-2">@include('profile.partials.update-password-form')</div>
                                </div>
                            </div>
                            <!-- Tabs content -->

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
