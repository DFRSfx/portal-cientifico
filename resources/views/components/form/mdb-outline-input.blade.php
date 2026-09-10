@props([
    'inputMask' => '',
    'bag' => '',
    'divContainerExtraClass' => 'mb-4',
])

<div class="{{ $divContainerExtraClass }}">
    @if ($label)
        <label class="form-label" for="{{ $name }}">{{ __($labelValue) }}</label>
    @endif

    <input type="{{ $type }}" @if ($inputMask != '') data-input-mask="{{ $inputMask }}" @endif
    id="{{ $name }}"
    class="form-control @if ($bag == '') @error($name) is-invalid @enderror @else @error($name, $bag) is-invalid @enderror @endif"
    name="{{ $name }}" value="{{ $value }}" />

    @if ($bag == '')
        @error($name)
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    @else
        @error($name, $bag)
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    @endif
</div>
