<div class="pt-3 w-full">
    <button id="update-author-information" type="button"
        class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-emerald-700 hover:bg-emerald-800 active:bg-emerald-900 text-white text-xs font-semibold rounded-xl shadow-xs transition-colors cursor-pointer border border-emerald-700"
        data-author-id="{{ $authorId }}">
        <i class="fas fa-arrows-rotate text-xs"></i>
        <span>{{ __('Atualizar Ciência Vitae') }}</span>
    </button>

    <div id="update-message-container" class="mt-2 text-xs"></div>
</div>
@pushOnce('scripts')
    <script src="{{ asset('javascript/authors/updateProfile.js') }}"></script>
@endPushOnce
