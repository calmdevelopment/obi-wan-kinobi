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
    {{--
        Vue sets class="isTouchDevice" or class="" depending
        on the vue root data isTouchDevice (see app.js)
    --}}
    <div id="app" :class="{isTouchDevice}" @auth class="auth" @else class="guest" @endauth>
        <input type="checkbox" id="toggle-nav">
        <header class="global">
            <nav>
                <a href="{{ url('/') }}" class="home">
                    <i class="fa fa-home" aria-hidden="true"></i>
                    <span>Kinobi</span>
                </a>
                @guest
                    {{-- only applied when not logged in (guest) --}}
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
                    {{-- only applied when logged in --}}
                    <a class="user" href="#">
                        <i class="fa fa-user" aria-hidden="true"></i>
                        <span>{{ Auth::user()->name }}</span>
                    </a>
                    {{--TODO replace JS form submit --}}
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
        @auth
            {{-- only applied if authenticated / logged in --}}
            <div class="main-navigation">
                <nav aria-labelledby="main-navigation-header">
                    <h2 id="main-navigation-header">Navigation</h2>
                    <h3>Artikel</h3>
                    {{-- TODO replace dummy links with real routes once they are present --}}
                    <a href="#">
                        <i class="fa fa-file fa-2x" aria-hidden="true"></i>
                        <span>Neu</span>
                    </a>
                    <a href="#"><i class="fa fa-clock-o fa-2x" aria-hidden="true"></i>
                        <span>Letzte Artikel</span>
                    </a>
                    <a href="#"><i class="fa fa-folder fa-2x" aria-hidden="true"></i>
                        <span>Seitenstruktur</span>
                    </a>
                    <h3>Events und Filme</h3>
                    <a href="#">
                        <i class="fa fa-calendar-plus-o fa-2x" aria-hidden="true"></i>
                        <span>Neu</span>
                    </a>
                    <a href="#">
                        <span class="fa-stack">
                            <i class="fa fa-calendar-o fa-stack-2x" aria-hidden="true"></i>
                            <i class="fa fa-clock-o fa-stack-1x" aria-hidden="true"></i>
                        </span>
                        <span>Letzte Events</span>
                    </a>
                    <a href="#">
                        <i class="fa fa-calendar fa-2x" aria-hidden="true"></i>
                        <span>Nach Zeit</span>
                    </a>
                </nav>
            </div>
        @endauth
        <main>
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            @if (session('resent'))
                <div class="alert alert-success" role="alert">
                    {{ __('A fresh verification link has been sent to your email address.') }}
                </div>
            @endif
            @yield('content')
        </main>
        @auth
            {{-- only applied if authenticated / logged in --}}
            <div class="shortcut">
                <nav>
                    {{-- TODO replace dummy links with routes once they are present --}}
                    <a href="#">
                        <i class="fa fa-file fa-2x" aria-hidden="true"></i>
                        <span class="hidden">Neuer Artikel</span>
                    </a>
                    <a href="#">
                        <i class="fa fa-clock-o fa-2x" aria-hidden="true"></i>
                        <span class="hidden">Letzte Artikel</span>
                    </a>
                    <a href="#">
                        <i class="fa fa-calendar-plus-o fa-2x" aria-hidden="true"></i>
                        <span class="hidden">Neuer Event</span>
                    </a>
                    <a href="#">
                        <span class="fa-stack">
                            <i class="fa fa-calendar-o fa-stack-2x" aria-hidden="true"></i>
                            <i class="fa fa-clock-o fa-stack-1x" aria-hidden="true"></i>
                        </span>
                        <span class="hidden">Letzte Events</span>
                    </a>
                </nav>
                <label for="toggle-nav">☰</label>
            </div>
        @endauth
    </div>
</body>
</html>
