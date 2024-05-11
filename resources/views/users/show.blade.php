@extends('layouts.app')
@section('title', 'Profile: ' . $user->username)

@section('content')
    <div class="container bg-light p-4">
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

        @if(Session::has('user_edited'))
            <div class="alert alert-success" role="alert">
                User modified: {{Session::get('user_edited')->username}} at {{Session::get('user_edited')->updated_at}}
            </div>
        @endif

        @if(Session::has('user_edited_by_themself'))
            <div class="alert alert-success" role="alert">
                You edited your profile successfully at: {{Session::get('user_edited_by_themself')->updated_at}}
            </div>
        @endif
        <div class="row flex">
            <div class="col-auto d-flex align-items-center">
                <h1 class="mb-3">
                    @if(str_starts_with($user['profile_picture'],"placeholder"))
                        <img class="rounded-circle shadow-1-strong" src={{url($user->profile_picture)}} alt="{{$user['username']}} avatar" width="100" height="100"/>
                    @else
                        <img class="rounded-circle shadow-1-strong" src="{{URL::asset('storage/'.$user->profile_picture)}}" alt="{{$user['username']}} avatar" width="100" height="100"/>
                    @endif
                    Profile of {{$user->username}}
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
                                    @guest
                                        <a href="{{route('login')}}" class="btn btn-primary m-2"><i class="fa-solid fa-user-plus" style="color: #ffffff;"></i></a>
                                    @endguest
                                    @auth
                                        @if (Auth::user()->email_verified_at === null)
                                            <a href="{{route('verification.notice')}}" class="btn btn-primary m-2"><i class="fa-solid fa-user-plus" style="color: #ffffff;"></i></a>
                                        @else
                                            <a class="btn btn-primary m-2 followButton" id="{{$user->id}}">
                                                @if(in_array($user->id, array_column(Auth::user()->follows()->get()->toArray(), 'id')))
                                                    <i class="fa-solid fa-user-minus" style="color: #ffffff;" id="unfollowed"></i>
                                                @else
                                                    <i class="fa-solid fa-user-plus"style="color: #ffffff;" id="followed"></i>
                                                @endif
                                            </a>
                                        @endif
                                    @endauth
                                    <a href="{{route('users.message', ['id'=>$user['id']])}}" class="btn btn-primary m-2"><i class="fa-solid fa-message" style="color: #ffffff;"></i></a>
                                    @if (Auth::user()->isAdmin())
                                        @if (!$user->isAdmin())
                                                @if (!$user->isModerator())
                                                    <a href="{{route('users.addModerator', ["id"=>$user['id']])}}" class="btn btn-warning m-2">Add Moderator</a>
                                                @else
                                                    <a href="{{route('users.removeModerator', ["id"=>$user['id']])}}" class="btn btn-warning m-2">Remove Moderator</a>
                                                @endif
                                                <a href="{{route('users.edit', [$user])}}" class="btn btn-warning m-2">Modify User</a>
                                                <a href="{{route('users.delete', ["id"=>$user['id']])}}" class="btn btn-danger m-2">Delete User</a>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td colspan="2">
                                    @if (Auth::user()->isAdmin())
                                        <a href="{{route('users.edit', [$user])}}" class="btn btn-primary m-2">Edit Profile</a>
                                    @else
                                        <a href="{{route('users.userEdit', [$user])}}" class="btn btn-primary m-2">Edit Profile</a>
                                    @endif
                                    <a href="{{route('users.changePassword', ['id'=>$user['id']])}}" class="btn btn-primary m-2">Change Password</a>
                                </td>
                            </tr>
                        @endif
                    @endauth
                </table>
            </div>
            <table class="table table-bordered table-striped-columns table-hover">
                <tr>
                    <td class="col-2"><b>Followed By</b></td>
                    <td class="col-2" id="followcount">{{$followedBy}}</td>
                    <td class="col-2"><b>Follows</b></td>
                    <td class="col-2"><small>Users:</small> {{$follows}} <small>Brands:</small> {{$followedBrands}}</td>
                </tr>
                <tr>
                    <td class="col-3"><b>Likes Received</b></td>
                    <td class="col-3" id="likecount">{{$likedBy}}</td>
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

        <div class="d-flex flex-row justify-content-center" id="pagination">
            {{ $images->links() }}
        </div>

        <div class="row mt-3">
            <div>
                <div class="row">

                    @forelse ($images as $image)
                        <div class="col-12 col-md-6 col-lg-4 mb-3 d-flex align-self-stretch" style="flex: 50%, width: 50%">
                            <div class="card bg-light border-secondary mt-3 ratio-4x3 w-100">
                                @if(str_starts_with($image['image'],"placeholder"))
                                    <img class="card-img-top" src="{{url($image['image'])}}" alt="{{$image['user']['username']}} - {{App\Models\Brand::find($image->type['brand_id'])['name']}} {{$image['type']['type']}} image">
                                @else
                                    <img class="card-img-top" src="{{URL::asset('storage/'.$image['image'])}}" alt="{{$image['user']['username']}} - {{App\Models\Brand::find($image->type['brand_id'])['name']}} {{$image['type']['type']}} image">
                                @endif
                                <div class="card-body">
                                    <h5 class="card-title mb-0"><a href="{{route('brands.show', ['brand'=>$image->type['brand_id']])}}" class="text-decoration-none">{{App\Models\Brand::find($image->type['brand_id'])['name']}}</a> <a href="{{route('types.show', ['type'=>$image->type['id']])}}" class="text-decoration-none">{{ $image->type['type'] }}</a></h5>
                                    <p class="small mb-0">
                                        <span class="me-2">
                                            <i class="fas fa-user"></i>
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
                                            <span>{{ $image->created_at }}</span>
                                        </span>
                                    </p>

                                </div>
                                <div class="card-footer">
                                @guest
                                    <span><a href="{{route('login')}}" class="btn" id="{{$image['id']}}"><i class="fa-regular fa-heart fa-xl" style="color: #ff0000;" id="liked"></i> <b>{{count($image->likedBy()->get()->toArray())}}</b></a></span>
                                @endguest
                                @auth
                                    @if (Auth::user()->email_verified_at === null)
                                        <span><a href="{{route('verification.notice')}}" class="btn" id="{{$image['id']}}"><i class="fa-regular fa-heart fa-xl" style="color: #ff0000;" id="liked"></i> <b>{{count($image->likedBy()->get()->toArray())}}</b></a></span>
                                    @else
                                        <span>
                                            <a class="btn likeButton" id="{{$image['id']}}">
                                                @if (in_array($image['id'], array_column(Auth::user()->likedImages()->get()->toArray(), 'id')))
                                                    <i class="fa-solid fa-heart fa-xl" style="color: #ff0000;" id="disliked"></i>
                                                @else
                                                    <i class="fa-regular fa-heart fa-xl" style="color: #ff0000;" id="liked"></i>
                                                @endif
                                                <b>{{count($image->likedBy()->get()->toArray())}}</b>
                                            </a>
                                        </span>
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

                <div class="d-flex flex-row justify-content-center mt-3" id="pagination">
                    {{ $images->links() }}
                </div>

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{asset('js/like.js')}}"></script>
    <script src="{{asset('js/follow.js')}}"></script>
@endsection
