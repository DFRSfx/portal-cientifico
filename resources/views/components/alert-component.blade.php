<div x-data="{ show: true }"
     x-show="show"
     x-init="setTimeout(() => show = false, 3500)"
     x-transition:leave="transition ease-in duration-300 transform"
     x-transition:leave-start="opacity-100 translate-y-0"
     x-transition:leave-end="opacity-0 translate-y-2"
     id="alertExample"
     role="alert"
     class="fixed bottom-6 right-6 z-50 flex items-center gap-3 px-4 py-3 rounded-xl shadow-xl border text-sm font-semibold {{ $color === 'danger' ? 'bg-rose-50 text-rose-900 border-rose-200' : 'bg-emerald-50 text-emerald-900 border-emerald-200' }}">
    <i class="{{ $iconClass }} text-base"></i>
    <span class="flex-1">{{ __("" . $message) }}</span>
    <button type="button" @click="show = false" class="text-slate-400 hover:text-slate-700 cursor-pointer border-0 bg-transparent p-1 leading-none text-lg" aria-label="Close">
        &times;
    </button>
</div>