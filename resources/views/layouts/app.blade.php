<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <script src="{{ asset('js/app.js') }}" defer></script>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    {{-- --}}
    <div id="app" :class="{isTouchDevice}">
        <input type="checkbox" id="toggle-nav">
        <header class="global" ref="topNavigation">
            <nav style="--num-items: 3">
                <a href="{{ url('/') }}" class="home">
                    <i class="fa fa-home" aria-hidden="true"></i>
                    <span>Kinobi</span>
                </a>
                @guest
                    <a href="{{ route('login') }}" class="login">
                        <i class="fa fa-user" aria-hidden="true"></i>
                        <span>{{ __('Login') }}</span>
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="register">
                            <i class="fa fa-user-plus" aria-hidden="true"></i>
                            <span>{{ __('Register') }}</span>
                        </a>
                    @endif
                @else
                    <a class="user">
                        <i class="fa fa-user" aria-hidden="true"></i>
                        <span>{{ Auth::user()->name }}</span>
                    </a>

                    <a href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                                     document.getElementById('logout-form').submit();"
                        class="logout">
                        <i class="fa fa-sign-out" aria-hidden="true"></i>
                        <span>{{ __('Logout') }}</span>
                    </a>
                @endguest
            </nav>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </header>
        <div class="main-navigation">
            <a href="#">asdfasdf</a>
            <a href="#">asdfasdf</a>
            <a href="#">asdfasdf</a>
            <a href="#">asdfasdf</a>
            <a href="#">asdfasdf</a>
            <a href="#">asdfasdf</a>
            <a href="#">asdfasdf</a>
            <a href="#">asdfasdf</a>
            <a href="#">asdfasdf</a>
            <a href="#">asdfasdf</a>
            <a href="#">asdfasdf</a>
            <a href="#">asdfasdf</a>
            <a href="#">asdfasdf</a>
            <a href="#">asdfasdf</a>
            <a href="#">asdfasdf</a>
            <a href="#">asdfasdf</a>
            <a href="#">asdfasdf</a>
            <a href="#">asdfasdf</a>
            <a href="#">asdfasdf</a>
            <a href="#">asdfasdf</a>

        </div>
        <main class="py-4" ref="main">
            @yield('content')
            <p>asdf ölaskdfj öalsdkfj öalsdfj öasldfjk asödlkfjldkfj aösldfkj asödlfj asdölfkj  öalsdfja
            öalsdkfj asödlfkjas fölka öadlskjf öasldfkj asödlfkj asödlfkj aöslfkj ölasdkjf öasldkfj öasldfkj
            öalsdfkja söflkas ö aaksiweifnwein iw aivn asdlf aiwe f</p>
            <p>asdf ölaskdfj öalsdkfj öalsdfj öasldfjk asödlkfjldkfj aösldfkj asödlfj asdölfkj  öalsdfja
                öalsdkfj asödlfkjas fölka öadlskjf öasldfkj asödlfkj asödlfkj aöslfkj ölasdkjf öasldkfj öasldfkj
                öalsdfkja söflkas ö aaksiweifnwein iw aivn asdlf aiwe f</p>
            <p>asdf ölaskdfj öalsdkfj öalsdfj öasldfjk asödlkfjldkfj aösldfkj asödlfj asdölfkj  öalsdfja
                öalsdkfj asödlfkjas fölka öadlskjf öasldfkj asödlfkj asödlfkj aöslfkj ölasdkjf öasldkfj öasldfkj
                öalsdfkja söflkas ö aaksiweifnwein iw aivn asdlf aiwe f</p>
            <p>asdf ölaskdfj öalsdkfj öalsdfj öasldfjk asödlkfjldkfj aösldfkj asödlfj asdölfkj  öalsdfja
                öalsdkfj asödlfkjas fölka öadlskjf öasldfkj asödlfkj asödlfkj aöslfkj ölasdkjf öasldkfj öasldfkj
                öalsdfkja söflkas ö aaksiweifnwein iw aivn asdlf aiwe f</p>
            <p>asdf ölaskdfj öalsdkfj öalsdfj öasldfjk asödlkfjldkfj aösldfkj asödlfj asdölfkj  öalsdfja
                öalsdkfj asödlfkjas fölka öadlskjf öasldfkj asödlfkj asödlfkj aöslfkj ölasdkjf öasldkfj öasldfkj
                öalsdfkja söflkas ö aaksiweifnwein iw aivn asdlf aiwe f</p>
        </main>
        @auth
            <div class="shortcut">
                <nav>
                    <a class="nav-logout" href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                        <i class="fa fa-sign-out" aria-hidden="true"></i>
                        <span>{{ __('Logout') }}1</span>
                    </a>
                </nav>
                <label for="toggle-nav">☰</label>
            </div>
        @endauth
    </div>
</body>
</html>
