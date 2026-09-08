<x-guest-layout>

    
    <div class="container pt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Set Password') }}</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route("password-set.store") }}">
                            @csrf
                            
                            <input type="hidden" name="token" value="{{ $requestToken }}">


                            <div class="row mb-3">
                                <label for="password"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                                <div class="col-md-6">
                                    <x-form.mdb-input label="0" labelValue="Password" type="password"
                                        name="password" value="" />
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Password Confirmation') }}</label>

                                <div class="col-md-6">
                                    <x-form.mdb-input label="0" labelValue="Password" type="password"
                                        name="password_confirmation" value="" />
                                </div>
                            </div>
                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit"
                                        class="btn btn-rounded gain-bg text-white ripple-surface-dark"
                                        data-mdb-ripple-color="dark" style="background-color: rgb(51, 100, 43);">
                                        {{ __('Login') }}
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
        <x-alert-component id="alert-message" error-message="1" message="Incorrect Email or Password!" />
    @endif

</x-guest-layout>
