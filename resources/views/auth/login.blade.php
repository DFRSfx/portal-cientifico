<x-guest-layout>
    <x-slot:title>
        {{ __('Iniciar Sessão') }}
    </x-slot>

    <!-- Interactive Dual-Modal Auth (Login Mode) -->
    <x-auth-modal default-tab="login" :is-standalone="true" />
</x-guest-layout>