<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Online shop</title>

@yield('page-style-files')

<!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

</head>
<body>
<div id="app">
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/categories') }}">
                Online shop
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">

                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                    <!-- Authentication Links -->
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                      style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <div class="wrapper">
        @yield('content')
    </div>
    <footer id="colophon" class="site-footer" role="contentinfo">
        <div class="footer_wrapper" style="display: flex; justify-content: space-between; width: 100%; height: inherit">
            <div class="element-container pull-left" style="display: flex;align-items: center;">
                <div class="pull-left">
                    <a href="/"><img alt="test logo"
                                     src="https://www.test.local/wp-content/uploads/2020/03/main-logo.png"
                                     style="width: 100px;">
                    </a>
                </div>
                <div style="margin-left: 20px;">
                    <div><span style="color: #ffffff;">7 Test street,</span></div>
                    <div><span style="color: #ffffff;">Test 121 32 Sofia.</span></div>
                    <div><span style="color: #ffffff;">+359-00114468</span></div>
                </div>
            </div>
            <div class="element-container pull-right" style="display: flex; align-items: center;">
                <div class="second-footer-info"
                     style="display: flex; flex-direction: column; margin-left: 10px;">
                    <div><span style="color: #ffffff;">45 Ivan Geshov street, 10431 Sofia.</span></div>
                    <div><span style="color: #ffffff;">+359-3322514</span></div>
                    <div><span style="color: #ffffff;"><a style="color: #ffffff;" href="mailto:info@laravel.test">info@test.local</a></span>
                    </div>
                </div>
            </div>
        </div>
    </footer>

</div>

</body>

@yield('page-js-files')
@yield('page-js-script')

</html>
