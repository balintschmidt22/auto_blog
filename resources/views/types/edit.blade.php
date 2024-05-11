@extends('layouts.app')
@section('title', 'Edit type')

@section('content')
    <div class="container bg-light p-4">
        <h1 class="mb-4">Edit type: {{$type['type']}} ({{$type->brand()->get()->first()['name']}})</h1>

        <form action="{{ route('types.update', ['type'=>$type['id']]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('patch')

            <div class="form-group row mb-3">
                <label for="brand" class="col-sm-2 col-form-label"><b>Brand*</b></label>
                <div class="col-sm-8">
                    <select name="brand" id="brand" class="form-control @error('brand') is-invalid @enderror">
                        <option value="{{ $brand['name'] }}">{{$brand['name']}}</option>
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
                <label for="name" class="col-sm-2 col-form-label"><b>Name*</b></label>
                <div class="col-sm-8">
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ $type['type'] }}" autofocus>
                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Edit</button>
            </div>
        </form>
    </div>
@endsection
