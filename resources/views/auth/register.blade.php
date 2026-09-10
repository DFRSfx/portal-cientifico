<x-guest-layout>
    <x-slot:title>
        {{ __('Efetuar Registo') }}
    </x-slot>

    <!-- Interactive Dual-Modal Auth (Register Mode) -->
    <x-auth-modal default-tab="register" :is-standalone="true" />
</x-guest-layout>