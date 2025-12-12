<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content={{csrf_token()}}>

        <title>@stack('title'){{ isset($title) ? $title : 'Hitchhikers' }}</title>
        <link rel="icon" type="image/x-icon" href="{{asset('favicon.ico')}}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @stack('head')
    </head>
    <body>
        @include('layouts.header')
        {{--@include('layouts.navigation')--}}
        
        <main style="padding-top:80px">
            @yield('content')
        </main>
        
        @include('layouts.footer')

        @stack('scripts')
    </body>
</html>