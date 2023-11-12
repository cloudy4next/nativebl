<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Toffee Analytics') }}</title>

    <!-- Scripts -->
    <link rel="stylesheet" href="/css/app.css">

    <style>

        body, html {
            margin: 0;
            height: 100%;
        }

        .bg-image {
            background-image: url("{{asset('img/login-background.png')}}");
            background-size: 100% 100%; /* This will ensure the full height of the image is visible */
            background-position: left center;
            background-repeat: no-repeat;
            height: 100%;
        }

        .h-100 {
            height: 100%;
        }

        .form-container {
            padding: 50px;
        }

        .logo img {
            max-width: 250px; /* Or your desired width */
            max-height: 250px; /* Or your desired height */
        }

        .sign-in {
            background: #9600AD;
            color: #ffffff;
        }

        .btn:hover {
            background-color: #B34FD3;
            color: #ffffff;
        }

        @media (max-width: 767px) {
            .bg-image {
                background-image: none;
                height: 0; /* Or alternatively: display: none; */
                padding: 0; /* If padding is set, ensure it’s removed. */
            }

            .img-fluid {
                display: none;
            }
        }

    </style>
</head>

<body>
{{ $slot }}


<script>
    window.base_url = "{{ url('/') }}";
    window.csrf_token = "{{ csrf_token() }}";
</script>

<script src="/js/app.js"></script>

@stack('scripts')

@if (session('success'))
    <x-native::toast type="success" message="{{ session('success') }}"/>
@elseif((session('info')))
    <x-native::toast type="info" message="{{ session('info') }}"/>
@elseif((session('warning')))
    <x-native::toast type="warning" message="{{ session('warning') }}"/>
@elseif((session('error')))
    <x-native::toast type="error" message="{{ session('error') }}"/>
@endif
</body>

</html>

