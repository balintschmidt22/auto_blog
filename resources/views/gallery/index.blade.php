@extends('layouts.app')
@section('title', 'Photos')

@section('content')
<div class="container">
    <div class="row">
        <h1>Gallery</h1>
    </div>
    @auth

        @if(Session::has('fav_added'))
        <div class="alert alert-success" role="alert">
            New image liked {{Session::get('fav_added')->id}}
        </div>
        @endif

        @if(Session::has('fav_removed'))
        <div class="alert alert-success" role="alert">
            Image disliked: {{Session::get('fav_removed')->id}}
        </div>
        @endif
    @endauth

    <div class="d-flex flex-row justify-content-center">
        {{-- TODO: Pagination --}}
        {{ $images->links() }}
    </div>

    <div class="row mt-3">
        <div>
            <div class="row">
                {{-- TODO: Read images from DB --}}

                @forelse ($images as $image)
                    <div class="col-12 col-md-6 col-lg-4 mb-3 d-flex align-self-stretch" style="flex: 50%">
                        <div class="card ratio-4x3 w-100">
                            @if(str_starts_with($image['image'],"https"))
                                <img class="card-img-top" src="{{$image['image']}}" alt="{{$image['user']['username']}} - {{$image['type']['name']}} image">
                            @else
                                <img class="card-img-top" src="{{"storage/".$image['image']}}" alt="{{$image['user']['username']}} - {{$image['type']['name']}} image">
                            @endif
                            <div class="card-body">
                                {{-- TODO: Brand - Type --}}
                                <h5 class="card-title mb-0">{{App\Models\Brand::find( $image->type['brand_id'])['name']}} - {{ $image->type['type'] }}</h5>
                                <p class="small mb-0">
                                    <span class="me-2">
                                        <i class="fas fa-user"></i>
                                        {{-- TODO: User --}}
                                        <span>{{
                                            $image->user ? $image->user->username : 'Unknown'
                                        }}</span>
                                    </span>

                                    <span class="me-2">
                                        <i class="bi bi-geo-alt-fill"></i>
                                        <span>
                                            {{$image->location}}
                                        </span>
                                    </span>
                                    <br>
                                    <span>
                                        <i class="far fa-calendar-alt"></i>
                                        {{-- TODO: Date --}}
                                        <span>{{ $image->created_at }}</span>
                                    </span>
                                </p>

                            </div>
                            <div class="card-footer">
                                {{-- TODO: Link --}}
                                <a href="{{route('gallery.show', $image)}}" class="btn btn-primary">
                                    <span>View image</span> <i class="fas fa-angle-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-warning" role="alert">
                            No images found!
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="d-flex flex-row justify-content-center">
                {{-- TODO: Pagination --}}
                {{ $images->links() }}
            </div>

        </div>
    </div>
</div>
@endsection
