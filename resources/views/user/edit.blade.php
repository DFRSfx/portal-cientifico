<x-app-layout>
    <div class="container pt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Editar Utilizador') }}</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('user.update', $user->id) }}">
                            @csrf
                            @method('PATCH')

                            <input type="hidden" name="redirect_to" value="{{ old('redirect_to', url()->previous()) }}">

                            <div class="mb-3">
                                <label class="form-label">{{ __('Nome') }}</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">{{ __('Email') }}</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">{{ __('Ciencia Vitae Id') }}</label>
                                <input type="text" name="ciencia_vitae" class="form-control @error('ciencia_vitae') is-invalid @enderror" value="{{ old('ciencia_vitae', $user->ciencia_vitae) }}">
                                @error('ciencia_vitae')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">{{ __('Tipo') }}</label>
                                <input type="text" name="type" class="form-control @error('type') is-invalid @enderror" value="{{ old('type', $user->type) }}" required>
                                @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">{{ __('Entidade') }}</label>
                                <input type="text" name="entidade" class="form-control @error('entidade') is-invalid @enderror" value="{{ old('entidade', $user->entidade) }}">
                                @error('entidade')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">{{ __('Ativo') }}</label>
                            </div>

                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="is_isla" id="is_isla" {{ old('is_isla', $user->is_isla) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_isla">{{ __('ISLA') }}</label>
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="mark_verified" id="mark_verified">
                                <label class="form-check-label" for="mark_verified">{{ __('Marcar email como verificado agora') }}</label>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">{{ __('Guardar') }}</button>
                                <a href="{{ route('user.active') }}" class="btn btn-outline-secondary">{{ __('Cancelar') }}</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
