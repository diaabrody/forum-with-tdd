<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        .level{
            display: flex;
        }
        .flex{
            display: flex;
            flex: 1;
            flex-direction: column;
        }
        [v-cloak] { display:none; }
        .material-icons.md-18 { font-size: 18px; }

    </style>
    <link rel="stylesheet" href="{{ asset('css/material-icons.css') }}" />
    @yield('css')

        <script>
             window.App = {!! json_encode([
                 'signedIn' => Auth::check(),
                   'user' => Auth::user(),
             ]) !!}
        </script>
</head>
<body>
    <div id="app">
        @include('layouts.nav')
        <main class="py-4">
            @yield('content')
        </main>
    <flash message="{{session('flash')}}"></flash>
    </div>
</body>
</html>
