@extends('layouts.app')
@section('title', 'Edit your profile')

@section('content')
    <div class="container">
        <h1 class="mb-4">Edit user: {{$user['username']}}</h1>

        <form action="{{ route('users.userUpdate', ['id'=>$user['id']]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('patch')

            <div class="form-group row mb-3">
                <label for="country" class="col-sm-2 col-form-label"><b>Country*</b></label>
                <div class="col-sm-8">
                    <input type="text" class="form-control @error('country') is-invalid @enderror" id="country" name="country" value="{{ $user['country'] }}">
                    @error('country')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <div class="form-group row mb-3">
                <label for="image" class="col-sm-2 col-form-label"><b>Profile Picture</b></label>
                <div class="col-sm-10">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <input type="file" class="form-control-file" id="image" name="image">
                            </div>
                            <div id="cover_preview" class="col-12 d-none">
                                <p>Image preview:</p>
                                <img id="cover_preview_image" src="#" alt="Image preview" width="100px">
                            </div>
                        </div>
                    </div>
                </div>

                @error('image')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Edit</button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script src="{{asset('js/image.js')}}"></script>
@endsection
