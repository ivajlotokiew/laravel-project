<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <link href="{{ asset('css/styles.css') }}" rel="stylesheet" type="text/css" >

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
        <style>

        </style>
    </head>
    <body>
        <div id="app">
            <div class="flex-center position-ref full-height">

                <div class="content">
                    <div class="sub-title">Welcome to our</div>
                    <div class="title m-b-md">
                        Products shop
                    </div>

                    <div class="links">
                        @if (Route::has('login'))
                                @auth
                                    <a href="{{ url('/home') }}">Home</a>
                                @else
                                    <a href="{{ route('login') }}">Login</a>

                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}">Register</a>
                                    @endif
                                @endauth
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
