@extends('layouts.app')
@section('title', 'Modify image')

@section('content')
    <div class="container bg-light">
        <h1 class="mb-4">Modify image</h1>

        <form action="{{ route('gallery.update', ['gallery'=>$image['id']]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('patch')

            <div class="row">
                <div class="col-lg-6 col-md-7 col-sm-10">
                    <div class="form-group row mb-3">
                        <label for="image" class="col-sm-2 col-form-label"><b>Image</b></label>
                        <div class="col-sm-10">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <input type="file" class="form-control-file" id="image" name="image">
                                    </div>
                                </div>
                            </div>
                        </div>

                        @error('image')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group row mb-3">
                        <label for="location" class="col-sm-2 col-form-label"><b>Location*</b></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control @error('location') is-invalid @enderror" id="location" name="location" value="{{ $image['location'] }}">
                            @error('location')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="brand" class="col-sm-2 col-form-label"><b>Brand*</b></label>
                        <div class="col-sm-8">
                            <select name="brand" id="brand" class="form-control @error('brand') is-invalid @enderror">
                                <option value="{{ $image->brand()['name'] }}">{{$image->brand()['name']}}</option>
                                @forelse($brands as $brand)
                                    <option value="{{$brand->name}}">{{$brand->name}}</option>
                                @empty
                                    <div class="col-12">
                                        <div class="alert alert-warning" role="alert">
                                            No brands!
                                        </div>
                                    </div>
                                @endforelse
                            </select>
                            @error('brand')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="type" class="col-sm-2 col-form-label"><b>Type*</b></label>
                        <div class="col-sm-8">
                            <select name="type" id="type" class="form-control @error('type') is-invalid @enderror">
                                <option value="{{ old('type')}}">{{old('type')}}</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div id="cover_preview" class="col-lg-6 col-md-2 col-sm-10 d-none">
                    <img id="cover_preview_image" src="#" alt="Image preview" height="240px">
                </div>
            </div>

            <div class="text-left">
                <button type="submit" class="btn btn-primary mt-2"><i class="fas fa-save"></i> Modify</button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script src="{{asset('js/image.js')}}"></script>
    <script src="{{asset('js/updateDropdown.js')}}"></script>
@endsection
