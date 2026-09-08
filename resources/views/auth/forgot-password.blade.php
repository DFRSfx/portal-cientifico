<x-guest-layout>

    <div class="container pt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Recover yout password') }}</div>
                    <div class="card-body">

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf
                            <div class="row mb-3">
                                <label for="email"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Email') }}</label>

                                <div class="col-md-6">
                                    <x-form.mdb-input label="0" labelValue="" type="text" name="email"
                                        value="{{ old('email') }}" />
                                </div>
                            </div>
                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit"
                                        class="btn btn-rounded gain-bg text-white ripple-surface-dark"
                                        data-mdb-ripple-color="dark"
                                        style="background-color: rgb(51, 100, 43);">{{ __('Email Password Reset Link') }}</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
