@props([
    'class' => 'rounded-full',
    'height' => null,
    'width' => null,
])

@php
    $h = $height ? (is_numeric($height) ? $height . 'px' : $height) : null;
    $w = $width ? (is_numeric($width) ? $width . 'px' : ($h ?? null)) : ($h ?? null);

    $styles = ['object-fit: cover', 'border-radius: 9999px', 'display: block'];
    if ($h) {
        $styles[] = "height: {$h}";
        $styles[] = "min-height: {$h}";
    } else {
        $styles[] = "height: 100%";
    }
    if ($w) {
        $styles[] = "width: {$w}";
        $styles[] = "min-width: {$w}";
    } else {
        $styles[] = "width: 100%";
    }
    $styleString = implode('; ', $styles) . ';';

    $pixelHeight = is_numeric($height) ? (int)$height : null;
    $pixelWidth = is_numeric($width) ? (int)$width : ($pixelHeight ?? null);
@endphp

@if ($profileImageIsPublic)
    <img src="https://www.cienciavitae.pt/fotos/publico/{{ $cienciaVitae }}.jpg" alt="user"
        onerror="this.onerror=null;this.src='{{ asset('/logo/user.jpg') }}';" class="{{ $class }}"
        style="{{ $styleString }}"
        @if ($pixelHeight) height="{{ $pixelHeight }}" @endif
        @if ($pixelWidth) width="{{ $pixelWidth }}" @endif
        loading="lazy">
@else
    <img src="{{ asset('/logo/user.jpg') }}" alt="user" class="{{ $class }}"
        style="{{ $styleString }}"
        @if ($pixelHeight) height="{{ $pixelHeight }}" @endif
        @if ($pixelWidth) width="{{ $pixelWidth }}" @endif
        loading="lazy">
@endif