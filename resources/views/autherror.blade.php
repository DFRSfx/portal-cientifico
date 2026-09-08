<x-app-layout>

    <x-slot:title>
        Autores
    </x-slot>

    <div class="container p-4">
        <div class="col ps-5">
            <div class="row">
                <div class="card">
                    @if ($errors->any())
                        <h2>{{ __('ERRO') }}</h2>
                        @if ($errors->has('message'))
                            <div class="alert alert-danger">
                                {{ $errors->first('message') }}
                            </div>
                        @endif
                        @foreach ($errors as $error)
                            <p> {{ $error }}
                            </p>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
    
</x-app-layout>