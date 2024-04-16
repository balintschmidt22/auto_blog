@extends('layouts.app')
@section('title', 'Home Page')

@section('content')

<div class="container w-50">
    {{-- @if (Session::has('email_verified'))
        <div class="alert alert-success" role="alert">
            Email verified successfully!
        </div>
    @endif --}}
    <div class="d-flex justify-content-center align-items-center">
        <img src="{{url('autoblog_logo.png')}}" alt="AutoBlog Logo" class="img-fluid mt-3">
    </div>
    <div class="d-flex justify-content-center align-items-center">
        <h2 class="mt-5">Welcome to the site!</h2>
    </div>
    <div class="d-flex justify-content-center align-items-center">
        <p class="mt-5">The goal of this project is to have more and more pictures of cars that you find interesting. You can upload any kind of cars, if you have a proper own image of it. On the site you can like images, follow users & brands, comment below images and message each other. If you want to join register today and start uploading! If you have any questions message the admin or if you don't have an account write an email to <a href="mailto:contact@autoblog.com" class="text-decoration-none">contact@autoblog.com</a></p>
    </div>
</div>
@endsection
