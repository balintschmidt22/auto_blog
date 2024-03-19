@extends('layouts.app')
@section('title', 'Edit type')

@section('content')
    <div class="container">
        <h1 class="mb-4">Edit type: {{$type['type']}} ({{$type->brand()->get()->first()['name']}})</h1>

        {{-- TODO: action, method, enctype --}}
        <form action="{{ route('types.update', ['type'=>$type['id']]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('patch')
            {{-- TODO: Validation --}}

            <div class="form-group row mb-3">
                <label for="name" class="col-sm-2 col-form-label"><b>Name</b></label>
                <div class="col-sm-8">
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" autofocus>
                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
            </div>
        </form>
    </div>
@endsection
