@extends('layouts.app')
@section('title', 'Upload')

@section('content')
    <div class="container">
        <h1 class="mb-4">Upload a photo</h1>

        @if(Session::has('image_uploaded'))
            <div class="alert alert-success" role="alert">
                Photo successfully uploaded at {{Session::get('image_uploaded')->created_at}}
            </div>
        @endif

        {{-- TODO: action, method, enctype --}}
        <form action="{{ route('gallery.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- TODO: Validation --}}

            <div class="form-group row mb-3">
                <label for="image" class="col-sm-2 col-form-label"><b>Image</b></label>
                <div class="col-sm-10">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <input type="file" class="form-control-file" id="image" name="image">
                            </div>
                            <div id="cover_preview" class="col-12 d-none">
                                <p>Image preview:</p>
                                <img id="cover_preview_image" src="#" alt="Image preview" height="240px">
                            </div>
                        </div>
                    </div>
                </div>

                @error('image')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group row mb-3">
                <label for="location" class="col-sm-2 col-form-label"><b>Location</b></label>
                <div class="col-sm-8">
                    <input type="text" class="form-control @error('location') is-invalid @enderror" id="location" name="location" value="{{ old('location') }}">
                    @error('location')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <div class="form-group row mb-3">
                <label for="brand" class="col-sm-2 col-form-label"><b>Brand</b></label>
                <div class="col-sm-8">
                    <select name="brand" id="brand" class="form-control @error('brand') is-invalid @enderror">
                        <option value="{{ old('brand') }}">{{old('brand')}}</option>
                        @forelse($brands as $brand)
                            <option value="{{$brand->id}}">{{$brand->name}}</option>
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
                <label for="type" class="col-sm-2 col-form-label"><b>Types</b></label>
                <div class="col-sm-8">
                    <select name="type" id="type" class="form-control @error('type') is-invalid @enderror">
                        <option value="{{ old('type') }}">{{old('type')}}</option>
                        @forelse($types as $type)
                            <option value="{{$type->id}}">{{$type->type}}</option>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-warning" role="alert">
                                    No types!
                                </div>
                            </div>
                        @endforelse
                    </select>
                    @error('type')
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

@section('scripts')
<script>
    const coverImageInput = document.querySelector('input#image');
    const coverPreviewContainer = document.querySelector('#cover_preview');
    const coverPreviewImage = document.querySelector('img#cover_preview_image');

    coverImageInput.onchange = event => {
        const [file] = coverImageInput.files;
        if (file) {
            coverPreviewContainer.classList.remove('d-none');
            coverPreviewImage.src = URL.createObjectURL(file);
        } else {
            coverPreviewContainer.classList.add('d-none');
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Initial population of "Types" dropdown based on the selected value of "Brand" dropdown
        updateTypeDropdown();

        // Event listener for change in "Brand" dropdown
        document.getElementById('brand').addEventListener('change', function () {
            // Update "Types" dropdown whenever "Brand" changes
            updateTypeDropdown();
        });

        // Function to update "Types" dropdown options based on the selected value of "Brand" dropdown
        function updateTypeDropdown() {
            var selectedBrand = document.getElementById('brand').value;
            console.log(selectedBrand)

            // Make an AJAX request to fetch types based on the selectedBrand
            var xhr = new XMLHttpRequest();
            xhr.open('GET', '/gallery/get-types?brand=' + encodeURIComponent(selectedBrand), true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // Clear existing options
                    var typeDropdown = document.getElementById('type');
                    typeDropdown.innerHTML = '';
                    // Populate "Types" dropdown with new options
                    data = xhr.responseText
                    var types = JSON.parse(data).types;
                    console.log(types)
                    for (var key in types) {
                            var option = document.createElement('option');
                            option.value = key;
                            option.text = types[key];
                            typeDropdown.appendChild(option);
                            console.log(option)
                    }
                }
            };
            xhr.send();
        }
    });
</script>
@endsection
