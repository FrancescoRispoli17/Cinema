<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://kit.fontawesome.com/84c04c9e02.js" crossorigin="anonymous"></script>
    <title>@yield('page-title')</title>
    @vite('resources/js/app.js')
</head>
<body>
    @include('shared.header')
    <main>
        @yield('main')
    </main>
    @include('shared.footer')

</body>
</html>