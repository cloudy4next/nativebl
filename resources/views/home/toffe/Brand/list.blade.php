<x-main-layout>
    <x-slot:title>
        NativeBL:: Brand List
    </x-slot>
    <x-native::crud-grid title="Brand List" />
    @push('styles')
        <style>
            .thead-purple tr th {
                background-color: #f0e6ee;
            }
        </style>
    @endpush
</x-main-layout>
