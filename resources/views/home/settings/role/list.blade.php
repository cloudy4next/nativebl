<x-main-layout>
    <x-slot:title>
        NativeBL:: Role List
    </x-slot>
    <x-native::crud-grid title="Role List" />

    @push('styles')
        <style>
            .thead-purple tr th {
                background-color: #f0e6ee;
            }
        </style>
    @endpush
</x-main-layout>
