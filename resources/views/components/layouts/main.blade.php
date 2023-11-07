<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'NativeBL Platform' }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Google Font-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link
        href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@300;400;500;600;700&amp;family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;0,1000;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900;1,1000&amp;display=swap"
        rel="stylesheet">

    <!-- CSS-->
    <link rel="stylesheet" href="/css/app.css">

</head>

<body>
<div class="wrapper">
    <x-menu/>
    <div class="main">

        <!-- NavBar-->
        <x-header/>

        <!-- Content-->
        {{ $slot }}

        <!-- Footer-->
        <x-footer/>
    </div>
</div>

<!-- JS-->
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
