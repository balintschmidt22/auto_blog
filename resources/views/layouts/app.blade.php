<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @if (View::hasSection('title'))
            AutoBlog - @yield('title')
        @endif
    </title>

    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">

    @vite(['public/sass/app.scss', 'public/js/app.js', 'public/css/app.css'])
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
            <div class="container">

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
            </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        <a href="/" class="btn btn-primary mb-3 topbar"><i class="fa-solid fa-house fa-lg" style="color: #ffffff;"></i></a>

                        <a href="{{route('gallery.create')}}" class="btn btn-primary mb-3 topbar">Upload <i class="fa-solid fa-upload fa-lg" style="color: #ffffff;"></i></a>
                        <a href="{{route('gallery.index')}}" class="btn btn-primary mb-3 topbar">Gallery <i class="fa-regular fa-images fa-lg" style="color: #ffffff;"></i></a>
                        <a href="{{route('users.index')}}" class="btn btn-primary mb-3 topbar">Users <i class="fa-solid fa-users fa-lg" style="color: #ffffff;"></i></a>
                        <a href="{{route('brands.index')}}" class="btn btn-primary mb-3 topbar">Brands <i class="fa-solid fa-car fa-lg" style="color: #ffffff;"></i></a>
                        <a href="{{route('favourites.index')}}" class="btn btn-primary mb-3 topbar">Favourites <i class="fa-solid fa-heart fa-lg" style="color: #ffffff;"></i></a>

                        <div class="dropdown">
                            <a class="topbar btn btn-primary mb-3 dropdown-toggle" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                Follows <i class="fa-solid fa-user-plus fa-lg" style="color: #ffffff;"></i>
                            </a >
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <li><a class="dropdown-item" href="{{route('follows.followedUsers')}}">Users</a></li>
                                <li><a class="dropdown-item" href="{{route('follows.followedBrands')}}">Brands</a></li>
                            </ul>
                        </div>

                        <a href="{{route('users.messageBox')}}" class="btn btn-primary mb-3 topbar">Messages <i class="fa-solid fa-envelope fa-lg" style="color: #ffffff;"></i></a>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        @auth
                            @if(str_starts_with(Auth::user()->profile_picture,"placeholder"))
                                <a href="{{route('users.show', ['user' => Auth::id()])}}" class="mb-3 topbar">
                                    <img class="rounded-circle shadow-1-strong me-1" src={{url(Auth::user()->profile_picture)}} alt="avatar" width="45" height="45">
                                </a>
                            @else
                                <a href="{{route('users.show', ['user' => Auth::id()])}}" class="mb-3 topbar">
                                    <img class="rounded-circle shadow-1-strong me-1" src="{{URL::asset('storage/'.Auth::user()->profile_picture)}}" alt="avatar" width="45" height="45">
                                </a>
                            @endif
                        @endauth

                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link text-white btn btn-primary topbar mb-3" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link text-white btn btn-primary topbar mb-3" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle text-white btn btn-primary topbar" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
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
            <section class="d-flex justify-content-center justify-content-lg-between p-3 border-bottom">
            <div class="container">
                <hr>
                <div class="d-flex flex-column align-items-center">
                    <div>
                        <span class="small">AutoBlog</span>
                        <span class="mx-1">·</span>
                        <span class="small">Laravel {{ app()->version() }}</span>
                        <span class="mx-1">·</span>
                        <span class="small">PHP {{ phpversion() }}</span>
                    </div>

                    <div>
                        <span class="small">Schmidt Bálint Márk</span>
                        <span class="mx-1">·</span>
                        <span class="small">FRJR89</span>
                    </div>
                </div>
            </div>
            </section>
        </footer>

        @yield('scripts')
    </div>
</body>
</html>
