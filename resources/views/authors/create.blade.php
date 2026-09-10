<x-app-layout>    
    
    <div class="container pt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('user.store') }}">
                            @csrf

                            <div class="row mb-3">
                                <label for="email"
                                    class="col-md-4 col-form-label text-md-end">{{ __('User Name') }}</label>

                                <div class="col-md-6">
                                    <x-form.mdb-input label="0" labelValue="" type="text" name="name" value="{{ old('name') }}" />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="email"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                                <div class="col-md-6">
                                    <x-form.mdb-input label="0" labelValue="" type="text" name="email" value="{{ old('email') }}" />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="email"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Ciencia_vitae Id') }}</label>

                                <div class="col-md-6">
                                    <x-form.mdb-input label="0" labelValue="" inputMask="xxx-xxx-xxx" type="text" name="ciencia_vitae" value="{{ old('ciencia_vitae') }}" />
                                </div>
                            </div>
                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary btn-rounded">
                                        {{ __('Save') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <x-alert-component id="alert-message" error-message="1" message="Error" />
    @elseif(\Session::has('success'))
        <x-alert-component id="alert-message" error-message="0" message="{{ \Session::get('success') }}" />
    @endif

</x-app-layout>