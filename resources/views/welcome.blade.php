@extends('layouts.app')
@section('title', 'Home Page')

@section('content')
    <h1>Home page</h1>
    <p>Welcome to the page!</p>
    <p>Here you can see all informations about the website. Choose from the functions above:</p>

    <ul>
{{--         <a href="{{route('matches.index', ['page'=>'1'])}}"><li>Matches</li></a>
        <a href="{{route('teams.index')}}"><li>Teams</li></a>
        <a href="{{route('table.index')}}"><li>Table</li></a>
        <a href="{{route('favourites.index')}}"><li>Favourites</li></a> --}}
        <a href="">Upload</a>
        <a href="">Photos</a>
        <a href="">Users</a>
        <a href="">Brands</a>
    </ul>
@endsection
