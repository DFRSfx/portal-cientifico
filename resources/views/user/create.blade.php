<x-app-layout>

    <div class="container pt-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                     

                        <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data"
                            class="mb-4">
                            @csrf
                            <div class="form-group">
                                <label for="file" class="form-label">{{ __('Choose File') }}</label>
                                <input type="file" name="file" id="file" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary">{{ __('Import Authors') }}</button>
                        </form>

                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">

                    <div class="card-body">

                        <form method="POST" action="{{ route('user.store') }}">
                            @csrf

                            <div class="row mb-3">
                                <label for="name"
                                    class="col-md-4 col-form-label text-md-end">{{ __('User Name') }}</label>

                                <div class="col-md-6">
                                    <x-form.mdb-input type="text" name="name" value="{{ old('name') }}" />
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="email"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                                <div class="col-md-6">
                                    <x-form.mdb-input labelValue="" type="text" name="email"
                                        value="{{ old('email') }}" />
                                </div>
                            </div>


                            <div class="row mb-3">
                                <label for="email"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Tipo de utilizador') }}</label>

                                @php

                                    $userTypes = [
                                        [
                                            'name' => 'Estudante',
                                            'value' => 'student',
                                        ],
                                        [
                                            'name' => 'Professor',
                                            'value' => 'teacher',
                                        ],
                                        [
                                            'name' => 'Administrativo',
                                            'value' => 'administrative',
                                        ],
                                    ];

                                @endphp

                                <div class="col-md-6" id="select-container">
                                    <select class="select" id="user-type-select" name="type"
                                        data-mdb-container="#select-container">

                                        @foreach ($userTypes as $type)
                                            <option value="{{ $type['value'] }}" @selected(old('type') == $type['value'])>
                                                {{ $type['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3" id="ciencia-vitae-container">
                                <label for="ciencia_vitae"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Ciencia_vitae Id') }}</label>

                                <div class="col-md-6">
                                    <x-form.mdb-input labelValue="" type="text" name="ciencia_vitae"
                                        value="{{ old('ciencia_vitae') }}" />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="email"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Is from ISLA') }}?</label>

                                <div class="col-md-6">
                                    <input type="hidden" name="is_isla" value="0">
                                    <input class="form-check-input" type="checkbox" name="is_isla" value="1"
                                        id="flexCheckDefault">
                                </div>
                            </div>
                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit"
                                        class="btn btn-rounded gain-bg text-white ripple-surface-dark"
                                        data-mdb-ripple-color="dark" style="background-color: rgb(51, 100, 43);">
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

    @error('errorMessage')
        <x-alert-component id="alert-message" error-message="1" message="{{ $message }}" />
    @enderror

    @if (\Session::has('success'))
        <x-alert-component id="alert-message" error-message="0" message="{{ \Session::get('success') }}" />
    @endif

    <script>
        $(document).ready(function() {

            $('#user-type-select').change(function(handler) {

                const selectedValue = handler.currentTarget.value

                if (selectedValue == "administrative") {
                    document.getElementById("ciencia-vitae-container").style.display = "none"
                } else {
                    document.getElementById("ciencia-vitae-container").style.display = "flex"
                }
            })

            if (document.getElementById("user-type-select").value == "administrative") document.getElementById(
                "ciencia-vitae-container").style.display = "none"
        })
    </script>

</x-app-layout>
