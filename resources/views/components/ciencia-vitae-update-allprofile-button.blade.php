<div class="list-group list-group-flush pt-3 mt-3">
    <button id="update-all-authors" type="button"
        class="btn btn-danger btn-rounded">
        {{ __('Atualizar TODOS os autores') }}
    </button>
    <div id="update-all-message-container" class="mt-2 position-relative"></div>
</div>

@pushOnce('scripts')
    <script src="{{ asset('javascript/authors/updateProfile2.js') }}" defer></script>
@endPushOnce