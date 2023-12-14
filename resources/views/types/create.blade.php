@extends('layouts.app')
@section('title', 'Add type')

@section('content')
    <div class="container">
        <h1 class="mb-4">Add a new type</h1>

        @if(Session::has('type_added'))
            <div class="alert alert-success" role="alert">
                Type successfully added with name: {{Session::get('type_added')->type}}, at: {{Session::get('type_added')->created_at}}
            </div>
        @endif

        {{-- TODO: action, method, enctype --}}
        <form action="{{ route('types.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- TODO: Validation --}}
            <div class="form-group row mb-3">
                <label for="brand" class="col-sm-2 col-form-label"><b>Brand</b></label>
                <div class="col-sm-8">
                    <select name="brand" id="brand" class="form-control @error('brand') is-invalid @enderror">
                        <option value="{{ old('brand') }}">{{old('brand')}}</option>
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
                <label for="name" class="col-sm-2 col-form-label"><b>Name</b></label>
                <div class="col-sm-8">
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}">
                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
            </div>
        </form>
    </div>
@endsection
