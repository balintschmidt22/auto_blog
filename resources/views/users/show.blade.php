@extends('layouts.app')
@section('title', 'Profile: ' . $user->username)

@section('content')
    <div class="container">
        @auth
            @if(Session::has('followed'))
                <div class="alert alert-success" role="alert">
                    New user followed: {{Session::get('followed')->username}}
                </div>
            @endif

            @if(Session::has('unfollowed'))
                <div class="alert alert-warning" role="alert">
                    User is no longer followed: {{Session::get('unfollowed')->username}}
                </div>
            @endif

            @if(Session::has('moderator_added'))
                <div class="alert alert-success" role="alert">
                    New moderator added: {{Session::get('moderator_added')->username}}
                </div>
            @endif

            @if(Session::has('moderator_removed'))
                <div class="alert alert-warning" role="alert">
                    Moderator removed: {{Session::get('moderator_removed')->username}}
                </div>
            @endif
        @endauth
        <div class="row flex">
            <div class="col-auto d-flex align-items-center">
                <h1 class="mb-3"><img class="rounded-circle shadow-1-strong"
                    src={{$user->profile_picture}} alt="avatar" width="100"
                    height="100" /> Profile of {{$user->username}}
                </h1>
            </div>
            <div class="col float-end">
                <table class="table table-bordered table-striped table-hover">
                    <tr>
                        <td><b>Role</b></td>
                        <td>
                            @if ($user->role === "adm")
                                Admin
                            @elseif ($user->role === "mod")
                                Moderator
                            @else
                                User
                            @endif
                        </td>
                    </tr>
                    <tr class="text-left">
                        <td><b>Country</b></td>
                        <td>{{$user->country}}</td>
                    </tr>
                    <tr>
                        <td><b>Email</b></td>
                        <td><a href="mailto:{{$user->email}}" target="_blank" class="text-decoration-none">{{$user->email}}</a></td>
                    </tr>
                    <tr>
                        <td><b>Joined</b></td>
                        <td>{{$user->created_at}}</td>
                    </tr>
                    <tr>
                        <td><b>Photos</b></td>
                        <td>{{$image_count}}</td>
                    </tr>
                    @auth
                        @if (Auth::id() != $user->id)
                            <tr>
                                <td colspan="2">
                                    @if(in_array($user['id'], array_column(Auth::user()->follows()->get()->toArray(), 'id')))
                                        <a href="{{route('follows.followUser', ['id'=>$user['id']])}}" class="btn btn-primary m-2"><i class="fa-solid fa-user-minus" style="color: #ffffff;"></i></a>
                                    @else
                                        <a href="{{route('follows.followUser', ['id'=>$user['id']])}}" class="btn btn-primary m-2"><i class="fa-solid fa-user-plus" style="color: #ffffff;"></i></a>
                                    @endif
                                    <a href="{{route('users.message', ['id'=>$user['id']])}}" class="btn btn-primary m-2"><i class="fa-solid fa-message" style="color: #ffffff;"></i></a>
                                    @if (Auth::user()->isAdmin())
                                        @if (!$user->isAdmin())
                                                @if (!$user->isModerator())
                                                    <a href="{{route('users.addModerator', ["id"=>$user['id']])}}" class="btn btn-warning m-2">Add Moderator</a>
                                                @else
                                                    <a href="{{route('users.removeModerator', ["id"=>$user['id']])}}" class="btn btn-warning m-2">Remove Moderator</a>
                                                @endif
                                                <a href="{{route('users.delete', ["id"=>$user['id']])}}" class="btn btn-danger m-2">Delete User</a>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td colspan="2">
                                    <a href="" class="btn btn-primary">Edit Profile</a>
                                </td>
                            </tr>
                        @endif
                    @endauth
                </table>
            </div>
            <table class="table table-bordered table-striped-columns table-hover">
                <tr>
                    <td class="col-3"><b>Followed By</b></td>
                    <td class="col-3">{{$followedBy}}</td>
                    <td class="col-3"><b>Follows</b></td>
                    <td class="col-3">{{$follows}}</td>
                </tr>
                <tr>
                    <td class="col-3"><b>Likes Received</b></td>
                    <td class="col-3">{{$likedBy}}</td>
                    <td class="col-3"><b>Likes Given</b></td>
                    <td class="col-3">{{$likesGiven}}</td>
                </tr>
                <tr>
                    <td class="col-3"><b>Comments Received</b></td>
                    <td class="col-3">{{$commentsGot}}</td>
                    <td class="col-3"><b>Commented On</b></td>
                    <td class="col-3">{{$commentedOn}}</td>
                </tr>
            </table>
        </div>

        @auth
            @if(Session::has('fav_added'))
            <div class="alert alert-success" role="alert">
                You liked:
                {{Session::get('fav_added')->type->brand()->get()->first()['name']}}
                {{Session::get('fav_added')->type['type']}} by {{Session::get('fav_added')->user['username']}}
            </div>
            @endif

            @if(Session::has('fav_removed'))
            <div class="alert alert-warning" role="alert">
                You disliked:
                {{Session::get('fav_removed')->type->brand()->get()->first()['name']}}
                {{Session::get('fav_removed')->type['type']}} by {{Session::get('fav_removed')->user['username']}}
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
                        <div class="col-12 col-md-6 col-lg-4 mb-3 d-flex align-self-stretch" style="flex: 50%, width: 50%">
                            <div class="card bg-light border-secondary mt-3 ratio-4x3 w-100">
                                @if(str_starts_with($image['image'],"http"))
                                    <img class="card-img-top" src="{{$image['image']}}" alt="{{$image['user']['username']}} - {{App\Models\Brand::find($image->type['brand_id'])['name']}} {{$image['type']['type']}} image">
                                @else
                                    <img class="card-img-top" src="{{URL::asset('storage/'.$image['image'])}}" alt="{{$image['user']['username']}} - {{App\Models\Brand::find($image->type['brand_id'])['name']}} {{$image['type']['type']}} image">
                                @endif
                                <div class="card-body">
                                    {{-- TODO: Brand - Type --}}
                                    <h5 class="card-title mb-0"><a href="{{route('brands.show', ['brand'=>$image->type['brand_id']])}}" class="text-decoration-none">{{App\Models\Brand::find($image->type['brand_id'])['name']}}</a> <a href="{{route('types.show', ['type'=>$image->type['id']])}}" class="text-decoration-none">{{ $image->type['type'] }}</a></h5>
                                    <p class="small mb-0">
                                        <span class="me-2">
                                            <i class="fas fa-user"></i>
                                            {{-- TODO: User --}}
                                            <span><a href="{{route('users.show', ['user'=>$image->user['id']])}}" class="text-decoration-none link-primary">{{$image->user->username}}</a></span>
                                        </span>

                                        <span class="me-2">
                                            <i class="bi bi-geo-alt-fill"></i>
                                            <span>
                                                {{$image->location}}
                                            </span>
                                        </span>
                                        <br>
                                        <span class="me-2">
                                            <i class="far fa-calendar-alt"></i>
                                            {{-- TODO: Date --}}
                                            <span>{{ $image->created_at }}</span>
                                        </span>
                                    </p>

                                </div>
                                <div class="card-footer">
                                    {{-- TODO: Link --}}
                                    @guest
                                        <span><a href="{{route('favourites.add', ['id'=>$image['id']])}}" class="btn"><i class="fa-regular fa-heart fa-xl" style="color: #ff0000;"></i> <b>{{count($image->likedBy()->get()->toArray())}}</b></a></span>
                                    @endguest
                                    @auth
                                        @if(in_array($image['id'], array_column(Auth::user()->likedImages()->get()->toArray(), 'id')))
                                            <span><a href="{{route('favourites.add', ['id'=>$image['id']])}}" class="btn"><i class="fa-solid fa-heart fa-xl" style="color: #ff0000;"></i> <b>{{count($image->likedBy()->get()->toArray())}}</b></a></span>
                                        @else
                                            <span><a href="{{route('favourites.add', ['id'=>$image['id']])}}" class="btn"><i class="fa-regular fa-heart fa-xl" style="color: #ff0000;"></i> <b>{{count($image->likedBy()->get()->toArray())}}</b></a></span>
                                        @endif
                                    @endauth
                                    <span>
                                        <a class="btn"><i class="fa-regular fa-comment fa-xl" style="color: #149f36;"></i> <b>{{count($image->comments()->get()->toArray())}}</b></a>
                                    </span>
                                    <a href="{{route('gallery.show', $image)}}" class="btn btn-primary float-end">
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

                <div class="d-flex flex-row justify-content-center mt-3">
                    {{-- TODO: Pagination --}}
                    {{ $images->links() }}
                </div>

            </div>
        </div>
    </div>
    @endsection
