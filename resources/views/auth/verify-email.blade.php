<x-guest-layout>
    <x-slot:title>
        {{ __('Verify your email address') }}
    </x-slot>

    <!-- Interactive Verify Email Modal -->
    <x-verify-email-modal :is-standalone="true" />
</x-guest-layout>
