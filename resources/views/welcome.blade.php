@extends('layouts.app')
@section('title', 'Home Page')

@section('content')
<div class="container">
    <h1>Home page</h1>
    <p>Welcome to the page!</p>
    <p>Here you can see all informations about the website. Choose from the functions above:</p>

    <ul>
        <a href="{{route('gallery.create')}}"><button class="btn btn-primary mb-3">Upload <x-feathericon-upload /></button></a>
        <a href="{{route('gallery.index')}}"><li>Gallery</li></a>
        <a href="{{route('users.index')}}"><li>Users</li></a>
        <a href="{{route('brands.index')}}"><li>Brands</li></a>
    </ul>
</div>
@endsection
