<div class="list-group list-group-flush pt-3 mt-3">
    <button id="update-all-authors" type="button"
        class="btn btn-rounded ripple-surface-dark text-white"
        style="background-color:#c92a2a;" data-mdb-ripple-color="dark">
        {{ __('Atualizar TODOS os autores') }}
    </button>
    <div id="update-all-message-container" class="mt-2 position-relative"></div>
</div>

@pushOnce('scripts')
    <script src="{{ asset('javascript/authors/updateProfile2.js') }}" defer></script>
@endPushOnce