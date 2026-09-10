<div class="list-group list-group-flush pt-3">
    <button id="update-author-information" type="button"
        class="btn btn-primary btn-rounded"
        data-author-id="{{ $authorId }}">
        {{ __('Atualizar ciencia vitae') }}
    </button>

    <div id="update-message-container" class="mt-2 position-relative"></div>
</div>
@pushOnce('scripts')
    <script src="{{ asset('javascript/authors/updateProfile.js') }}"></script>
@endPushOnce
