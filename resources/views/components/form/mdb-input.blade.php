@props([
    'bag' => '',
])

<input type="{{ $type }}"  id="{{ $name }}" class="form-control  @if ($bag == '') @error($name) is-invalid @enderror @else @error($name, $bag) is-invalid @enderror @endif" name="{{ $name }}" value="{{ $value }}" />

@if ($bag == '')
    @error($name)
            <div class="invalid-feedback">{{ $message }}</div>        
    @enderror
@else
    @error($name, $bag)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
@endif