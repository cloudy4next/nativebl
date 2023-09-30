<x-main-layout>
    <x-slot:title>
        NativeBL:: Single Campaign Report
    </x-slot>
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
                        <p class="card-text">{{ $data['ctr'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="col-md-2">
                <div class="card h-100">
                    <div class="card-body">
                        <i class="fas fa-question-circle"></i>
                        <h6 class="card-title">Total Clicks</h6>
                        <p class="card-text">{{ $data['clicks'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Card 5 -->
            <div class="col-md-2">
                <div class="card h-100">
                    <div class="card-body">
                        <i class="fas fa-question-circle"></i>
                        <h6 class="card-title">Total View</h6>
                        <p class="card-text">{{ $data['view'] }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-native::crud-grid title="Single Campaign Report" />
</x-main-layout>
