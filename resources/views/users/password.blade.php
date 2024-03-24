@extends('layouts.app')
@section('title', 'Change password')

@section('content')
    @if(Session::has('password_changed'))
        <div class="alert alert-success" role="alert">
            Password changed successfully!
        </div>
    @endif

    @if(Session::has('password_error'))
        <div class="alert alert-warning" role="alert">
            Old password doesn't match!
        </div>
    @endif

    <div class="container">
        <h1 class="mb-4">Change password</h1>

        <form action="{{ route('users.updatePassword', ['id'=>Auth::id()]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('patch')

            <div class="form-group row mb-3">
                <label for="old_password" class="col-sm-2 col-form-label"><b>Old Password</b></label>
                <div class="col-sm-8">
                    <input type="password" class="form-control @error('old_password') is-invalid @enderror" id="old_password" name="old_password">
                    @error('old_password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <div class="form-group row mb-3">
                <label for="new_password" class="col-sm-2 col-form-label"><b>New Password</b></label>
                <div class="col-sm-8">
                    <input type="password" class="form-control @error('new_password') is-invalid @enderror" id="new_password" name="new_password">
                    @error('new_password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <div class="form-group row mb-3">
                <label for="new_password_confirmation" class="col-sm-2 col-form-label"><b>Confirm New Password</b></label>
                <div class="col-sm-8">
                    <input type="password" class="form-control @error('new_password_confirmation') is-invalid @enderror" id="new_password_confirmation" name="new_password_confirmation">
                    @error('new_password_confirmation')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Change password</button>
            </div>
        </form>
    </div>
@endsection
