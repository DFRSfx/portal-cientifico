@props([
    'class' => 'rounded-circle',
    'height' => '40',
])

@if ($profileImageIsPublic)
    <img src="https://www.cienciavitae.pt/fotos/publico/{{ $cienciaVitae }}.jpg" alt="user"
        onerror="this.onerror=null;this.src='{{ asset('/logo/user.jpg') }}';" class="{{ $class }}"
        height="{{ $height }}" width="auto" loading="lazy">
@else
    <img src="{{ asset('/logo/user.jpg') }}" alt="user" class="{{ $class }}" height="{{ $height }}"
        width="auto" loading="lazy">
@endif