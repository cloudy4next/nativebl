<x-main-layout>
    <x-slot:title>
        NativeBL:: Campaign List
    </x-slot>
    <x-native::crud-grid title="Campaign List" />

    @push('styles')
        <style>
            .thead-purple tr th {
                background-color: #f0e6ee;
            }
        </style>
    @endpush
</x-main-layout>
