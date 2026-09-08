<x-guest-layout>

    <div class="container pt-5" style="max-width: 500px; margin-top:50px;">
        <div class="row">
            <form method="POST" action="{{ route('password.store') }}">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <x-form.mdb-input label="1" labelValue="Email" type="email" name="email" value="{{ old('email', $request->email) }}" />

                <x-form.mdb-input label="1" labelValue="Password" type="password" name="password" value="" />

                <x-form.mdb-input label="1" labelValue="Confirm Password" type="password" name="password_confirmation"
                    value="" />

                <!-- Submit button -->
                <button type="submit" class="btn btn-primary btn-block">{{ __('Reset Password') }}</button>
            </form>
        </div>
    </div>
    
</x-guest-layout>
