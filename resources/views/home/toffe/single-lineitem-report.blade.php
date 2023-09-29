<x-main-layout>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">

    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <div class="content">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Campaing Report</h5>

                <div class="mb-3" style="float: right;">
                    <input type="text" name="daterange" id="date-range-picker" />
                </div>

            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-boarded">
                        <thead>
                            <tr>
                                <th scope="col">Date</th>
                                <th scope="col">Impression</th>
                                <th scope="col">Clicks</th>
                                <th scope="col">Complete Views</th>
                                <th scope="col">Active Viewable Impression</th>
                                <th scope="col">Viewable Impression (%)</th>
                                <th scope="col">CTR</th>
                                <th scope="col">Completion Rate</th>
                            </tr>
                        </thead>
                        <tbody id="campaign-table-body">
                            @foreach ($data as $item)
                                <tr>
                                    <td class="text-center">{{ $item['individual_date'] }}</td>
                                    <td class="text-center">{{ $item['impression'] }}</td>
                                    <td class="text-center">{{ $item['clicks'] }}</td>
                                    <td class="text-center">{{ $item['complete_views'] }}</td>
                                    <td class="text-center">{{ $item['active_viewable_impression'] }}</td>
                                    <td class="text-center">{{ $item['viewable_impression'] }} %</td>
                                    <td class="text-center">{{ $item['ctr'] }} %</td>
                                    <td class="text-center">{{ $item['completion_rate'] }} %</td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            // Initialize the date range picker
            $('#date-range-picker').daterangepicker({
                startDate: moment().subtract(7, 'days'),
                endDate: moment(),
                opens: 'left',
                maxSpan: {
                    days: 7
                },
            });

            // Function to update table data based on selected date range
            function updateTableData(id, startDate, endDate) {
                console.log(startDate.format('YYYY-MM-DD HH:mm:ss'));
                $.ajax({
                    url: "/all-campaign/campaign-range-data",
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id,
                        startDate: startDate.format('YYYY-MM-DD HH:mm:ss'),
                        endDate: endDate.format('YYYY-MM-DD HH:mm:ss')
                    },
                    success: function(data) {
                        displayCampaignData(data.data);

                    },
                    error: function(error) {
                        console.error('Error:', error);
                    }
                });
            }

            // Add an event listener for date range changes
            $('#date-range-picker').on('apply.daterangepicker', function(ev, picker) {
                const url = window.location.href; // Get the current URL
                const id = parseIdFromUrl(url);

                const startDate = picker.startDate;
                const endDate = picker.endDate;
                updateTableData(id, startDate, endDate);
            });

            function parseIdFromUrl(url) {
                const parts = url.split('/');
                return parts[parts.length - 3];
            }

            function displayCampaignData(data) {
                var tableBody = $('#campaign-table-body tbody');
                tableBody.empty();

                $.each(data, function(index, campaign) {
                    var row = '<tr>' +
                        '<td>' + campaign.individual_date + '</td>' +
                        '<td>' + campaign.impression + '</td>' +
                        '<td>' + campaign.clicks + '</td>' +
                        '<td>' + campaign.complete_views + '</td>' +
                        '<td>' + campaign.active_viewable_impression + '</td>' +
                        '<td>' + campaign.viewable_impression + '</td>' +
                        '<td>' + campaign.ctr + '</td>' +
                        '<td>' + campaign.completion_rate + '</td>' +
                        '</tr>';

                    tableBody.append(row);
                });
            }

        });
    </script>
</x-main-layout>
