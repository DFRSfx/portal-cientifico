<x-guest-layout>

    <div class="container pt-5" style="max-width: 500px; margin-top:50px;">

        <div class="text-center">

            <h1 class="mb-3 h2 ">{{ __('Verify your email address') }}</h1>
            <h5 class="mb-3 text-muted">We have sent a verification link to {{ auth()->user()->email }}</h5>

            @if (auth()->user()->hasVerifiedEmail())
                <p>
                    O seu email ja foi confirmado. Aguarde a aprovacao do administrador para aceder ao dashboard.
                </p>
            @else
                <p class=" ">
                    Click on the link to complete the verification process.
                    You might need to check your spam folder
                </p>
            @endif
            
        </div>


        <div class="row">

            <div class="col">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf

                    <!-- Submit button -->
                    <button type="submit"
                        class="btn btn-primary btn-block">{{ __('Resend Verification Email') }}</button>
                </form>
            </div>

            <div class="col">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button type="submit" class="btn btn-primary btn-block">{{ __('Log Out') }}</button>

                </form>
            </div>
        </div>

        @if (session('status') == 'verification-link-sent')
                <div class="text-center">
                    {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                </div>
        @endif


    </div>

    
</x-guest-layout>
