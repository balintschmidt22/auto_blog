@extends('layouts.app')
@section('title', 'Add brand')

@section('content')
    <div class="container bg-light p-4">
        <h1 class="mb-4">Add a new brand</h1>

        @if(Session::has('brand_added'))
            <div class="alert alert-success" role="alert">
                Brand successfully added with name: {{Session::get('brand_added')->name}}, at: {{Session::get('brand_added')->created_at}}
            </div>
        @endif

        <form action="{{ route('brands.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group row mb-3">
                <label for="name" class="col-sm-2 col-form-label"><b>Name*</b></label>
                <div class="col-sm-8">
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}">
                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <div class="form-group row mb-3">
                <label for="country" class="col-sm-2 col-form-label"><b>Country*</b></label>
                <div class="col-sm-8">
                    <input type="text" class="form-control @error('country') is-invalid @enderror" id="country" name="country" value="{{ old('country') }}">
                    @error('country')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <div class="form-group row mb-3">
                <label for="image" class="col-sm-2 col-form-label"><b>Image*</b></label>
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
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script src="{{asset('js/image.js')}}"></script>
@endsection
