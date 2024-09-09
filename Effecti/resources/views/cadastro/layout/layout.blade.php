<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Default Title')</title>
    <link rel="stylesheet" href="{{ asset('css/estilo.css') }}">
</head>

<body>

    <!-- Header -->
    @yield('header')
    <!------------->

    <!-- Main content -->
    <div class="container">
        @yield('content')
    </div>

    <!-- Footer -->
    @yield('footer')
    <!------------->

</body>

</html>
