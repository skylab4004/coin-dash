<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('includes.head')
</head>
<body class="bg-gray-300">
@include('includes.header')

@yield('content')

@include('includes.footer')
</body>
</html>