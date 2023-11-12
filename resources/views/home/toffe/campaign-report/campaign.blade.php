<x-main-layout>
    <x-slot:title>
        NativeBL:: ALL Campaign
    </x-slot:title>
    <x-native::crud-grid title="All Campaign" />

    @push('styles')
        <style>
            .thead-purple tr th {
                background-color: #f0e6ee;
            }
        </style>
    @endpush
</x-main-layout>
