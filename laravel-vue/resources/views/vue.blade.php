<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ isset($viewType) && $viewType == 'ict' ? 'MBSTU ICT' : (isset($viewType) && $viewType == 'cse' ? 'MBSTU CSE' : 'MBSTU Home Page') }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <!-- Styles -->
    <style>
        body {
            font-family: 'Figtree', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
    </style>

    @php
        echo $viewType . '<br>';
        echo $theme . '<br>';
        echo $components . '<br>';
        echo $page . '<br>';
    @endphp
    <script>
        window.appConfig = {
            subdomainType: "{{ $viewType ?? 'default' }}",
            fullDomain: "{{ $fullDomain ?? 'mbstu.localhost' }}",
            siteData : "{{ $siteData ?? 'site.data' }}",
            theme : "{{ $theme ?? 'theme.data' }}",
            components : "{{ $components ?? 'components.data' }}",
            page : "{{ $page ?? 'page.data' }}",
        };
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <!-- <div id="app"></div> -->
</body>
</html>
