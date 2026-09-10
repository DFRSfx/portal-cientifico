@props([
    'class' => 'rounded-circle',
    'height' => '40',
    'width' => null,
])

@php
    $h = is_numeric($height) ? $height . 'px' : $height;
    $w = $width ? (is_numeric($width) ? $width . 'px' : $width) : $h;
@endphp

@if ($profileImageIsPublic)
    <img src="https://www.cienciavitae.pt/fotos/publico/{{ $cienciaVitae }}.jpg" alt="user"
        onerror="this.onerror=null;this.src='{{ asset('/logo/user.jpg') }}';" class="{{ $class }}"
        style="height: {{ $h }}; width: {{ $w }}; min-width: {{ $w }}; min-height: {{ $h }}; object-fit: cover; border-radius: 9999px;"
        height="{{ $height }}" width="{{ $height }}" loading="lazy">
@else
    <img src="{{ asset('/logo/user.jpg') }}" alt="user" class="{{ $class }}"
        style="height: {{ $h }}; width: {{ $w }}; min-width: {{ $w }}; min-height: {{ $h }}; object-fit: cover; border-radius: 9999px;"
        height="{{ $height }}" width="{{ $height }}" loading="lazy">
@endif