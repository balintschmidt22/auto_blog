@extends('layouts.app')
@section('title', 'Error 404')

@section('content')
    <div class="container mt-5 pt-5">
        <div class="alert alert-primary text-center">
            <h2 class="display-3">404</h2>
            <p class="display-5">Oops!
                @auth()
                    {{Auth::user()['username']}},
                @endauth
                Something is wrong.</p>
        </div>
    </div>
@endsection
