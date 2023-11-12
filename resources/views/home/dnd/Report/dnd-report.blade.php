<html lang="en">
<link rel="stylesheet" href="/css/app.css">
<style>
    .text-right {
        text-align: right !important;
    }

    .table-bordered>tbody>tr>td,
    .table-bordered>tbody>tr>th,
    .table-bordered>tfoot>tr>td,
    .table-bordered>tfoot>tr>th,
    .table-bordered>thead>tr>td,
    .table-bordered>thead>tr>th {
        border: 1px solid #000 !important;
        padding: 4px !important;
        vertical-align: middle;
    }

    section .table-bordered>tbody>tr>td,
    section .table-bordered>tbody>tr>th,
    section .table-bordered>thead>tr>td,
    section .table-bordered>thead>tr>th {
        text-align: center;
    }

    .table-bordered>tfoot {
        display: table-row-group;
    }

    .mb-20 {
        margin-bottom: 20px;
    }

    .page-break {
        page-break-after: always;
    }

    @media print {

        body,
        table {
            font-size: 12px;
        }

        html,
        body {
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
                <th>Channel Name</th>
                <th>Msisdn</th>
                <th>Request Type</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
                <tr>
                    <td>{{ $item['channel_name'] }}</td>
                    <td>{{ $item['msisdn'] }}</td>
                    <td>{{ $item['request_type'] }}</td>
                    <td>{{ $item['created_at'] }}</td>

                </tr>
            @endforeach
        </tbody>
    </table>
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>

</html>
