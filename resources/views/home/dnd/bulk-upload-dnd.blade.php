<x-main-layout>
    <x-slot:title>
        NativeBL:: DND Bulk Upload
    </x-slot>

    @php
        $allSegments = request()->segments();
        $segmentsToPass = array_map(function ($segment) {
            return urlencode($segment);
        }, array_slice($allSegments, 2));
        $queryString = request()->query();
    @endphp
    <div class="content">
        <div class="row">
            <div class="mt-3 text-end">
                <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                    <div class="btn-group" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Report Export
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <li>
                                <a href="{{ route('dnd.export', array_merge($segmentsToPass, $queryString, ['type' => 'excel'])) }}"
                                    target="_blank" class="dropdown-item">Excel</a>

                            </li>
                            <li>
                                <a href="{{ route('dnd.export', array_merge($segmentsToPass, $queryString, ['type' => 'csv'])) }}"
                                    target="_blank" class="dropdown-item">CSV</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <x-native::crud-grid title="DND Bulk Upload" />
        </div>
    </div>
</x-main-layout>
