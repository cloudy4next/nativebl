<x-main-layout>
    <x-slot:title>
        NativeBL:: Single Campaign Report
    </x-slot:title>

    @php
        $allSegments = request()->segments();
        // Re-encode segments to get the desired format
        $segmentsToPass = array_map(function($segment) {
            return urlencode($segment);
        }, array_slice($allSegments, 2));
        $queryString = request()->query();
    @endphp

    <div class="content">
        <div class="row">
            <div class="col-md-2">
                <div class="card h-100">
                    <div class="card-body">
                        <i class="fas fa-question-circle"></i>
                        <h6 class="card-title">Campaign Status</h6>
                        <p class="card-text">{{ $data['status'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="col-md-2">
                <div class="card h-100">
                    <div class="card-body">
                        <i class="fas fa-line-chart"></i>
                        <h6 class="card-title">Total Impression</h6>
                        <p class="card-text">{{ $data['impression'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="col-md-2">
                <div class="card h-100">
                    <div class="card-body">
                        <i class="fas fa-question-circle"></i>
                        <h6 class="card-title">Total CTR</h6>
                        <p class="card-text">{{ $data['ctr'] }} </p>
                    </div>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="col-md-2">
                <div class="card h-100">
                    <div class="card-body">
                        <i class="fas fa-question-circle"></i>
                        <h6 class="card-title">Total Clicks</h6>
                        <p class="card-text">{{ $data['clicks'] }} </p>
                    </div>
                </div>
            </div>

            <!-- Card 5 -->
            <div class="col-md-2">
                <div class="card h-100">
                    <div class="card-body">
                        <i class="fas fa-question-circle"></i>
                        <h6 class="card-title">Complete View</h6>
                        <p class="card-text">{{ $data['view'] }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-3 text-end">
            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                <div class="btn-group" role="group">
                    <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle"
                            data-bs-toggle="dropdown" aria-expanded="false">
                        Report Export
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                        <li>
                            <a href="{{ route('toffee.campaign.export', array_merge($segmentsToPass, $queryString, ['type' => 'excel'])) }}"
                               target="_blank" class="dropdown-item">Excel</a>

                        </li>
                        <li>
                            <a href="{{ route('toffee.campaign.export', array_merge($segmentsToPass, $queryString, ['type' => 'csv'])) }}"
                               target="_blank" class="dropdown-item">CSV</a>
                        </li>
                        <li>
                            <a href="{{ route('toffee.campaign.export', array_merge($segmentsToPass, $queryString, ['type' => 'pdf'])) }}"
                               target="_blank" class="dropdown-item">PDF</a>

                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <x-native::crud-grid title="{{$data['name']}} Campaign Report"/>

</x-main-layout>
