@props([
    'inputMask' => '',
    'bag' => '',
    'divContainerExtraClass' => 'mb-4',
    'label' => true,
    'labelValue' => '',
    'type' => 'text',
    'name' => '',
    'value' => '',
    'placeholder' => '',
    'disabled' => false,
])

@php
    $hasError = $bag === '' ? $errors->has($name) : $errors->getBag($bag)->has($name);
    $errorMessage = $bag === '' ? $errors->first($name) : $errors->getBag($bag)->first($name);
@endphp

<div class="{{ $divContainerExtraClass }}">
    @if ($label && !empty($labelValue))
        <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5" for="{{ $name }}">
            {{ __($labelValue) }}
        </label>
    @endif

    <div class="relative">
        <input type="{{ $type }}"
            @if ($inputMask != '') data-input-mask="{{ $inputMask }}" @endif
            id="{{ $name }}"
            name="{{ $name }}"
            value="{{ $value }}"
            @if ($placeholder) placeholder="{{ $placeholder }}" @endif
            @if ($disabled) disabled @endif
            class="w-full px-3.5 py-2.5 bg-white border rounded-xl text-xs sm:text-sm text-slate-900 placeholder-slate-400 shadow-2xs transition-all focus:outline-none focus:ring-2 disabled:bg-slate-100 disabled:text-slate-500 {{ $hasError ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-500/20 text-rose-900 bg-rose-50/20' : 'border-slate-300 hover:border-slate-400 focus:border-emerald-700 focus:ring-emerald-700/20' }}" />
    </div>

    @if ($hasError)
        <p class="text-xs text-rose-600 font-medium mt-1.5 flex items-center gap-1.5">
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="shrink-0">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            <span>{{ $errorMessage }}</span>
        </p>
    @endif
</div>
