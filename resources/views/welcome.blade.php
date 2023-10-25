@extends('layouts.app')
@section('title', 'Home Page')

@section('content')
<div class="container">
    <h1>Home page</h1>
    <p>Hi @auth{{Auth::user()->username}}@endauth, welcome to the page</p>
    <p>Here you can see all informations about the website. Choose from the functions above</p>
</div>
@endsection
