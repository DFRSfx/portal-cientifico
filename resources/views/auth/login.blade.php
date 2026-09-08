<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="container-fluid min-vh-100 d-flex " style="background: #f8f9fa;">
        <div class="row flex-grow-1">
            <!-- Coluna do formulário de login -->
            <div class="col-md-6 d-flex align-items-center justify-content-center" style="background: #f5f5f5ff;">
                <div style="width: 100%; max-width: 400px;">
                    <h2 class="text-center mb-4" style="font-weight: 700; font-size: 32px; color: #325223ff;">
                        <img src="{{ asset('logo/icon-portalcientifico.svg') }}" alt="Logo" style="width: 50px; margin-left: auto; margin-right: auto;">
                            {{ __("Portal Científico") }}
                    </h2>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __("CienciaVitae ID") }}</label>
                            <input type="text" name="username" id="email" class="form-control" required>
                        </div>
                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">{{ __("Password") }}</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>
                        <!-- Remember Me -->
                        <div class="mb-3 form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label" for="remember">{{ __("Remember Me") }}</label>
                        </div>
                        <button type="submit" class="btn btn-success w-100" style="background: #325223ff">Login</button>
                        @if (Route::has('password.request'))
                            <a class="btn btn-link d-block mt-2" href="{{ route('password.request') }}">
                                Forgot Your Password?
                            </a>
                        @endif
                    </form>
                    @if ($errors->any())
                        <x-alert-component id="alert-message" error-message="1" message="Incorrect Email or Password!" />
                    @endif
                </div>
            </div>
            <!-- Coluna de informações/lado direito -->
            <div class="col-md-6 d-flex align-items-center justify-content-center text-white"
                style="background: url('logo/image-portal.png') no-repeat center center;background-size: cover;position: relative;">

                <!-- Overlay escuro para dar contraste -->
                <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; 
                            background: rgba(50, 82, 35, 0.8);"></div>

                <div style="position: relative; z-index: 1;">
                    <h3 class="mb-4" style="font-weight: 700; font-size: 38px">{{ __("Junta-te ao Mundo Científico") }}</h3>
                        <ul style="font-size: 20px; line-height: 2.2;"> 
                            <li>{{ __("Aceda à sua produção científica") }}</li>
                            <li>{{ __("Veja os seus projetos e atividades") }}</li> 
                            <li>{{ __("Exportação de relatórios e dados") }}</li> 
                            <li>{{ __("Suporte para ISLA GAIA e CEOS.PP") }}</li>
                        </ul>
                </div>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <x-alert-component id="alert-message" error-message="1" message="Incorrect Email or Password!" />
    @endif

</x-guest-layout>
