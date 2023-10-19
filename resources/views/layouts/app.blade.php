<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Custom title -->
    <title>
        @if (View::hasSection('title'))
            AutoBlog - @yield('title')
        @endif
    </title>

    @vite(['resources/sass/app.scss', 'resources/js/app.js', 'resources/css/app.css'])
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
            @if (!Request::is("/"))
                <a href="/" class="topbar" id="homebtn"><button class="btn btn-primary mb-3">Back to home page</button></a>

                <a href="{{route('gallery.create')}}" class="topbar"><button class="btn btn-primary mb-3">Upload <x-feathericon-upload /></button></a>
                <a href="{{route('gallery.index')}}" class="topbar"><button class="btn btn-primary mb-3">Gallery</button></a>
                <a href="{{route('users.index')}}" class="topbar"><button class="btn btn-primary mb-3">Users</button></a>
                <a href="{{route('brands.index')}}" class="topbar"><button class="btn btn-primary mb-3">Brands</button></a>
            @else
                @auth
                    <p class="topbar">Hi {{Auth::user()->username}}!</p>
                @else
                    <p><small>(You need to login for some functions!)</small></p>
                @endauth
            @endif
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
            </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->username }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>

        <footer class="mb-4">
            <section class="d-flex justify-content-center justify-content-lg-between p-4 border-bottom">
            <div class="container">
                <hr>
                <div class="d-flex flex-column align-items-center">
                    <div>
                        <a href="" class="me-4 link-secondary">
                            <i class="fab fa-facebook-f"></i>
                          </a>
                          <a href="" class="me-4 link-secondary">
                            <i class="fab fa-twitter"></i>
                          </a>
                          <a href="" class="me-4 link-secondary">
                            <i class="fab fa-google"></i>
                          </a>
                          <a href="" class="me-4 link-secondary">
                            <i class="fab fa-instagram"></i>
                          </a>
                          <a href="" class="me-4 link-secondary">
                            <i class="fab fa-linkedin"></i>
                          </a>
                          <a href="" class="me-4 link-secondary">
                            <i class="fab fa-github"></i>
                          </a>
                    </div>
                    <div>
                        <span class="small">AutoBlog</span>
                        <span class="mx-1">·</span>
                        <span class="small">Laravel {{ app()->version() }}</span>
                        <span class="mx-1">·</span>
                        <span class="small">PHP {{ phpversion() }}</span>
                    </div>

                    <div>
                        <span class="small">Schmidt Bálint Márk - FRJR89</span>
                    </div>
                </div>
            </div>
            </section>
        </footer>

        @yield('scripts')
    </div>
</body>
</html>
