<x-app-layout>
    <div class="d-flex justify-content-center align-items-center" style="margin-top:100px;">
        <div class="container p-4" style="max-width: 500px;">
            <h3 class="text-center mb-4">{{ __("Efetuar Registo") }}</h3>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-3">
                    <div class="form-outline">
                        <input type="text" id="name" class="form-control @error('name') is-invalid @enderror"
                            name="name" value="{{ old('name') }}" autofocus autocomplete="name" />
                        <label class="form-label" for="name">{{ __("Name") }}</label>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <div class="form-outline">
                        <input type="text" id="email" class="form-control @error('email') is-invalid @enderror"
                            name="email" value="{{ old('email') }}" required autocomplete="email" />
                        <label class="form-label" for="email">E-mail</label>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <div class="form-outline">
                        <input type="password" id="password"
                            class="form-control @error('password') is-invalid @enderror" name="password" required
                            autocomplete="new-password" />
                        <label class="form-label" for="password">{{ __("Password") }}</label>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <div class="form-outline">
                        <input type="password" id="password_confirmation" class="form-control"
                            name="password_confirmation" required autocomplete="new-password" />
                        <label class="form-label" for="password_confirmation">{{ __("Confirm Password") }}</label>
                    </div>
                </div>

                <div class="mb-3">
                    <select class="form-select" id="entidade" name="entidade" required>
                        <option value="" disabled selected>{{ __("Escolha a Entidade") }}</option>
                        <option value="ISLA">ISLA</option>
                        <option value="CEOS.PP">CEOS.PP</option>
                    </select>
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input type="hidden" value="0" name="teacher">
                        <input class="form-check-input" type="checkbox" value="1" id="teacher-checkbox" name="teacher" />
                        <label class="form-check-label" for="teacher-checkbox">{{ __("Im a Teacher") }}</label>
                    </div>
                    <div class="form-check">
                        <input type="hidden" value="0" name="student">
                        <input class="form-check-input" type="checkbox" value="1" id="student-checkbox" name="student" />
                        <label class="form-check-label" for="student-checkbox">{{ __("Im a Student") }}</label>
                    </div>
                </div>

                <div id="teacher-container" style="display:none">
                    <div class="mb-3">
                        <div class="form-outline">
                            <input type="text" id="organization_name"
                                class="form-control @error('organization_name') is-invalid @enderror"
                                name="organization_name" value="{{ old('organization_name') }}" autocomplete="organization_name" />
                            <label class="form-label" for="organization_name">{{ __("School / Organization") }}</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-outline">
                            <input type="text" id="linkedin_url"
                                class="form-control @error('linkedin_url') is-invalid @enderror" name="linkedin_url"
                                value="{{ old('linkedin_url') }}" autocomplete="linkedin_url" />
                            <label class="form-label" for="linkedin_url">{{ __("Linkedin Profile URL") }}</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-outline">
                            <input type="tel" id="phone_number" name="phone_number" class="form-control"
                                value="{{ old('phone_number') }}" />
                            <label class="form-label" for="phone_number">{{ __("Phone number") }}</label>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 mb-3">{{ __("Registar") }}</button>

                <div class="text-center">
                    <a class="text-sm text-gray-600 hover:text-gray-900"
                        href="{{ route('login') }}">{{ __('Already registered?') }}</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $("#teacher-checkbox").on("click", function() {
                const container = $("#teacher-container");
                container.toggle(this.checked);
            });
        });
    </script>
</x-app-layout>
