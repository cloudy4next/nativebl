<x-main-layout>
    <x-slot:title>
        NativeBL:: User List
    </x-slot>
    <x-native::crud-grid title="User List" />
    @push('styles')
        <style>
            .thead-purple tr th {
                background-color: #f0e6ee;
            }
        </style>
    @endpush
</x-main-layout>
