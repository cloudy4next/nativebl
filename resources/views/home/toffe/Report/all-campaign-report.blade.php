<html lang="en">
<link rel="stylesheet" href="/css/app.css">
<style>
    .text-right {
        text-align: right !important;
    }

    .table-bordered > tbody > tr > td,
    .table-bordered > tbody > tr > th,
    .table-bordered > tfoot > tr > td,
    .table-bordered > tfoot > tr > th,
    .table-bordered > thead > tr > td,
    .table-bordered > thead > tr > th {
        border: 1px solid #000 !important;
        padding: 4px !important;
        vertical-align: middle;
    }

    section .table-bordered > tbody > tr > td,
    section .table-bordered > tbody > tr > th,
    section .table-bordered > thead > tr > td,
    section .table-bordered > thead > tr > th {
        text-align: center;
    }

    .table-bordered > tfoot {
        display: table-row-group;
    }

    .mb-20 {
        margin-bottom: 20px;
    }

    .page-break {
        page-break-after: always;
    }

    @media print {
        body, table {
            font-size: 12px;
        }

        html, body {
            height: auto;
        }
    }

    @page {
        margin-right: 5px;
        margin-left: 5px;
    }
</style>
<body>
<table class="table table-bordered">
    <thead>
    <tr>
        <th>Date</th>
        <th>Impression</th>
        <th>Clicks</th>
        <th>Complete Views</th>
        <th>Active Viewable Impression</th>
        <th>Viewable Impression (%)</th>
        <th>CTR</th>
        <th>Completion Rate</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($data as $item)
        <tr>
            <td>{{ $item['individual_date'] }}</td>
            <td>{{ $item['impression'] }}</td>
            <td>{{ $item['clicks'] }}</td>
            <td>{{ $item['complete_views'] }}</td>
            <td>{{ $item['active_viewable_impression'] }}</td>
            <td>{{ $item['viewable_impression'] }} %</td>
            <td>{{ $item['ctr'] }} %</td>
            <td>{{ $item['completion_rate'] }} %</td>
        </tr>
    @endforeach
    </tbody>
</table>
<script>
    window.onload = function () {
        window.print();
    }
</script>
</body>
</html>

