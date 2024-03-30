@extends('layouts.app')
@section('title', 'Upload')

@section('content')
    <div class="container bg-light">
        <h1 class="mb-4">Upload a photo</h1>

        @if(Session::has('image_uploaded'))
            <div class="alert alert-success" role="alert">
                Photo successfully uploaded at {{Session::get('image_uploaded')->created_at}}
            </div>
        @endif

        <form action="{{ route('gallery.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

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
                        <label for="type" class="col-sm-2 col-form-label"><b>Types</b></label>
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
                    <div class="text-left m-3">
                        <button type="submit" class="btn btn-primary mt-2"><i class="fas fa-upload"></i> Upload</button>
                    </div>
                </div>
                <div id="cover_preview" class="col-lg-6 col-md-2 col-sm-10 d-none">
                    <img id="cover_preview_image" src="#" alt="Image preview" height="240px">
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
<script>
    const coverImageInput = document.querySelector("input#image");
    const coverPreviewContainer = document.querySelector("#cover_preview");
    const coverPreviewImage = document.querySelector("img#cover_preview_image");

    coverImageInput.onchange = (event) => {
        const [file] = coverImageInput.files;
        if (file) {
            coverPreviewContainer.classList.remove("d-none");
            coverPreviewImage.src = URL.createObjectURL(file);
        } else {
            coverPreviewContainer.classList.add("d-none");
        }
    };

    document.addEventListener("DOMContentLoaded", function () {
        // const oldTypeValue = document.getElementById("type")[0].value;
        // const oldTypeText = document.getElementById("type")[0].text;
        // console.log(oldTypeText)
        // console.log(oldTypeValue)

        updateTypeDropdown();

        // if(oldTypeValue != ""){
        //     document.getElementById("type")[0].value = oldTypeValue

        //     document.getElementById("type")[0].text = oldTypeText
        // }

        document.getElementById("brand").addEventListener("change", function () {
            updateTypeDropdown();
        });

        // Function to update "Types" dropdown options based on the selected value of "Brand" dropdown
        function updateTypeDropdown() {
            var selectedBrand = document.getElementById("brand").value;

            if (selectedBrand != "") {
                axios.defaults.headers.common = {
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                };

                axios
                    .get("/gallery/gettypes", {
                        params: {
                            brand: selectedBrand,
                        },
                    })
                    .then((response) => {
                        const d = response;
                        const types = d.data;

                        var typeDropdown = document.getElementById("type");
                        typeDropdown.innerHTML = "";

                        for (var i in types) {
                            var option = document.createElement("option");
                            option.value = types[i]['id'];
                            option.text = types[i]['type'];
                            typeDropdown.appendChild(option);
                        }
                    })
                    .catch((error) => {
                        console.error("Error fetching data:", error);
                    });
            }
        }
    });
</script>
@endsection
