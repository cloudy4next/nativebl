<x-main-layout>
    <x-slot:title>
        NativeBL:: Single Campaign Report
    </x-slot:title>

    @php
        $allSegments = request()->segments();
        // Re-encode segments to get the desired format
        $segmentsToPass = array_map(function ($segment) {
            return urlencode($segment);
        }, array_slice($allSegments, 2));
        $queryString = request()->query();
    @endphp

    <div class="content">
        <h3>{{ $data['name'] }}</h3>
        <div class="row">
            <div class="col-md-2">
                <div class="card h-100">
                    <div class="card-body">
                        @if ($data['status'] == 'DELIVERING')
                            <i class="fa fa-circle" style="color:green"></i>
                        @elseif ($data['status'] == 'PAUSED')
                            <i class="fa fa-pause-circle-o" aria-hidden="true" style="color:purple"></i>
                        @else
                            <i class="fa fa-circle" style="color:red"></i>
                        @endif
                        <h6 class="card-title">Campaign Status</h6>
                        <p class="card-text">{{ $data['status'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="col-md-2">
                <div class="card h-100">
                    <div class="card-body">
                        <i class="fas fa-line-chart" style="color:purple"></i>
                        <h6 class="card-title">Total Impression</h6>
                        <p class="card-text">{{ $data['impression'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="col-md-2">
                <div class="card h-100">
                    <div class="card-body">
                        <i class="fa fa-bar-chart" style="color:purple"></i>
                        <h6 class="card-title">Total CTR</h6>
                        <p class="card-text">{{ $data['ctr'] }} </p>
                    </div>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="col-md-2">
                <div class="card h-100">
                    <div class="card-body">
                        <i class="fa fa-hand-pointer-o" style="color:purple"></i>
                        <h6 class="card-title">Total Clicks</h6>
                        <p class="card-text">{{ $data['clicks'] }} </p>
                    </div>
                </div>
            </div>

            <!-- Card 5 -->
            <div class="col-md-2">
                <div class="card h-100">
                    <div class="card-body">
                        <i class="fa fa-eye" style="color:purple"></i>
                        <h6 class="card-title">Complete Views</h6>
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

        <div class="tab ">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" href="#primary-tab-1" data-bs-toggle="tab" role="tab"
                        aria-selected="true">Table</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" href="#primary-tab-2" data-bs-toggle="tab" role="tab" aria-selected="false"
                        tabindex="-1">Graph</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="primary-tab-1" role="tabpanel">
                    <x-native::crud-grid />
                </div>
                <div class="tab-pane" id="primary-tab-2" role="tabpanel">
                    <div class="content">
                        <div class="mb-3">
                        </div>
                        <div class="row">
                            <div class="col-6 ">
                                <div class="card mb-3 flex-fill w-100 ">
                                    <div class="card-header">
                                        <h5 class="card-title">Impression</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart">
                                            <canvas id="chartjs-line"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card mb-3 flex-fill w-100">
                                    <div class="card-header">
                                        <h5 class="card-title">Complete</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart">
                                            <canvas id="chartjs-line-complete"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card mb-3 flex-fill w-100">
                                    <div class="card-header">
                                        <h5 class="card-title">Active Viewable Impression</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart">
                                            <canvas id="chartjs-line-AVI"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card mb-3 flex-fill w-100">
                                    <div class="card-header">
                                        <h5 class="card-title">Total Clicks</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart">
                                            <canvas id="chartjs-line-TC"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-main-layout>
<script>
    // Data retrieved from the controller
    const chartData = @json($dataset);

    // Extract data and labels
    const labels = chartData.map(item => item[0]);
    const values = chartData.map(item => parseFloat(item[1].replace(',', '')));
    const completeView = chartData.map(item => parseFloat(item[2].replace(',', '')));
    const AVIData = chartData.map(item => parseFloat(item[3].replace(',', '')));
    const TC = chartData.map(item => parseFloat(item[4].replace(',', '')));


    // Create a chart using Chart.js
    const ctx = document.getElementById('chartjs-line').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Impression Data',
                data: values,
                borderColor: 'rgba(150,18,113)',
                borderWidth: 1,
                fill: false
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });


    const ctx1 = document.getElementById('chartjs-line-complete').getContext('2d');
    new Chart(ctx1, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Complete Data',
                data: completeView,
                borderColor: 'rgba(150,18,113)',
                borderWidth: 1,
                fill: false
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    const ctx2 = document.getElementById('chartjs-line-AVI').getContext('2d');
    new Chart(ctx2, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Active Viewable Impression Data',
                data: AVIData,
                borderColor: 'rgba(150,18,113)',
                borderWidth: 1,
                fill: false
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    const ctx3 = document.getElementById('chartjs-line-TC').getContext('2d');
    console.log(TC);
    new Chart(ctx3, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Total Clicks',
                data: TC,
                borderColor: 'rgba(150,18,113)',
                borderWidth: 1,
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
