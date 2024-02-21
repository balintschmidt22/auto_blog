@extends('layouts.app')
@section('title', 'Photo')

@section('content')
<div class="container">
    <div>
        <h1 class="mb-4"> @if(str_starts_with($brand['image'],"https"))
            <img src="{{$brand['image']}}" class="img-thumbnail" alt="{{$brand['name']}} image" style="height:80px">
        @else
           <img src="{{URL::asset('storage/'.$brand['image'])}}" class="img-thumbnail" alt="{{$brand['name']}} image" style="height:80px">
        @endif

        <a href="{{route('brands.show', ['brand'=>$brand['id']])}}" class="text-decoration-none">{{$brand['name']}}</a> <a href="{{route('types.show', ['type'=>$type['id']])}}" class="text-decoration-none">{{$type['type']}}</a>
        @auth
            @if (Auth::user()->isAdmin())
                <a href="{{route('gallery.delete', ['id' => $image['id']])}}" class="btn btn-danger">Delete Image</a>
            @endif
        @endauth
        </h1>
    </div>

    @auth
        @if(Session::has('fav_added'))
        <div class="alert alert-success mb-4" role="alert">
            You liked:
            {{Session::get('fav_added')->type->brand()->get()->first()['name']}}
            {{Session::get('fav_added')->type['type']}} by {{Session::get('fav_added')->user['username']}}
        </div>
        @endif

        @if(Session::has('fav_removed'))
        <div class="alert alert-warning mb-4" role="alert">
            You disliked:
            {{Session::get('fav_removed')->type->brand()->get()->first()['name']}}
            {{Session::get('fav_removed')->type['type']}} by {{Session::get('fav_removed')->user['username']}}
        </div>
        @endif
    @endauth

    <div class="row">
        <div class="col">
            @if(str_starts_with($image['image'],"http"))
                <img src="{{$image['image']}}" class="img-fluid" style="max-width: 100%; height: auto" alt="{{$image['user']['username']}} - {{$image['type']['type']}} image">
            @else
                <img src="{{URL::asset('storage/'.$image['image'])}}" class="img-fluid" style="max-width: 100%; height: auto" alt="{{$image['user']['username']}} - {{$image['type']['type']}} image">
            @endif
        </div>
        <div class="col">
            @guest
                <span><a href="{{route('favourites.add', ['id'=>$image['id']])}}" class="btn"><i class="fa-regular fa-heart fa-2xl" style="color: #ff0000;"></i> <b>{{count($image->likedBy()->get()->toArray())}}</b></a></span>
            @endguest
            @auth
                @if(in_array($image['id'], array_column(Auth::user()->likedImages()->get()->toArray(), 'id')))
                    <span><a href="{{route('favourites.add', ['id'=>$image['id']])}}" class="btn"><i class="fa-solid fa-heart fa-2xl" style="color: #ff0000;"></i> <b>{{count($image->likedBy()->get()->toArray())}}</b></a></span>
                @else
                    <span><a href="{{route('favourites.add', ['id'=>$image['id']])}}" class="btn"><i class="fa-regular fa-heart fa-2xl" style="color: #ff0000;"></i> <b>{{count($image->likedBy()->get()->toArray())}}</b></a></span>
                @endif
            @endauth

            <table class="table table-bordered mt-3">
                <tr>
                    <td><i class="fas fa-user"></i></td>
                    <td><a href="{{route('users.show', ['user'=>$image->user['id']])}}" class="text-decoration-none link-primary">{{$image->user->username}}</a></td>
                </tr>
                <tr>
                    <td><i class="bi bi-geo-alt-fill"></i></td>
                    <td>{{$image->location}}</td>
                </tr>
                <tr>
                    <td><i class="far fa-calendar-alt"></i></td>
                    <td>{{ $image->created_at }}</td>
                </tr>
            </table>

            <table>
                    @forelse ($comments as $comment)
                    <tr>
                        <td>
                            {{App\Models\User::find($comment['user_id'])['username']}}
                        </td>
                        <td>
                            {{$comment['comment']}}
                        </td>
                        <td>
                            {{$comment['created_at']}}
                        </td>
                    </tr>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-warning" role="alert">
                                No comments so far
                            </div>
                        </div>
                    @endforelse


            </table>


        </div>
    </div>
</div>
@endsection
