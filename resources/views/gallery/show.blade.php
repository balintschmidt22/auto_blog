@extends('layouts.app')
@section('title', 'Photo')

@section('content')
<div class="container bg-light p-4">
    <div>
        <h1 class="mb-4">
            @if(str_starts_with($brand['image'],"placeholder"))
                <img src="{{url($brand['image'])}}" class="img-thumbnail" alt="{{url($brand['name'])}} image" style="height:80px">
            @else
                <img src="{{URL::asset('storage/'.$brand['image'])}}" class="img-thumbnail" alt="{{$brand['name']}} image" style="height:80px">
            @endif

            <a href="{{route('brands.show', ['brand'=>$brand['id']])}}" class="text-decoration-none">{{$brand['name']}}</a>
            <a href="{{route('types.show', ['type'=>$type['id']])}}" class="text-decoration-none">{{$type['type']}}</a>
            @auth
                @if(Auth::user()->isModerator())
                    <a href="{{route('gallery.edit', [$image])}}" class="btn btn-warning">Modify Image</a>
                @endif
                @if (Auth::user()->isAdmin())
                    <a href="{{route('gallery.delete', ['id' => $image['id']])}}" class="btn btn-danger">Delete Image</a>
                @endif
            @endauth
        </h1>
    </div>

    @if(Session::has('comment_added'))
    <div class="alert alert-success mb-4" role="alert">
        New comment added by you at
        {{Session::get('comment_added')->created_at}}
    </div>
    @endif

    @if(Session::has('comment_deleted'))
    <div class="alert alert-warning mb-4" role="alert">
        Comment by
        {{App\Models\User::find(Session::get('comment_deleted')->user_id)['username']}},
        written at
        {{Session::get('comment_deleted')->created_at}}
        deleted
    </div>
    @endif

    @if(Session::has('image_edited'))
        <div class="alert alert-success" role="alert">
            Photo successfully modified at {{Session::get('image_edited')->updated_at}}
        </div>
    @endif

    <div class="row">
        <div class="col-md-7 col-sm-12">
            @if(str_starts_with($image['image'],"placeholder"))
                <a href="{{url($image['image'])}}"><img src="{{url($image['image'])}}" class="img-fluid" style="max-width: 100%; height: auto" alt="{{$image['user']['username']}} - {{$image['type']['type']}} image"></a>
            @else
                <a href="{{URL::asset('storage/'.$image['image'])}}"><img src="{{URL::asset('storage/'.$image['image'])}}" class="img-fluid" style="max-width: 100%; height: auto" alt="{{$image['user']['username']}} - {{$image['type']['type']}} image"></a>
            @endif
        </div>
        <div class="col">
            <table class="table table-bordered table-striped">
                <tr>
                    <td>
                        @guest
                            <span><a href="{{route('login')}}" class="btn" id="{{$image['id']}}"><i class="fa-regular fa-heart fa-2xl" style="color: #ff0000;" id="liked"></i> <b>{{count($image->likedBy()->get()->toArray())}}</b></a></span>
                        @endguest
                        @auth
                            @if (Auth::user()->email_verified_at === null)
                                <span><a href="{{route('verification.notice')}}" class="btn" id="{{$image['id']}}"><i class="fa-regular fa-heart fa-2xl" style="color: #ff0000;" id="liked"></i> <b>{{count($image->likedBy()->get()->toArray())}}</b></a></span>
                            @else
                                <span>
                                    <a class="btn likeButton" id="{{$image['id']}}">
                                        @if (in_array($image['id'], array_column(Auth::user()->likedImages()->get()->toArray(), 'id')))
                                            <i class="fa-solid fa-heart fa-2xl" style="color: #ff0000;" id="disliked"></i>
                                        @else
                                            <i class="fa-regular fa-heart fa-2xl" style="color: #ff0000;" id="liked"></i>
                                        @endif
                                        <b>{{count($image->likedBy()->get()->toArray())}}</b>
                                    </a>
                                </span>
                            @endif
                        @endauth
                        <span>
                            <a class="btn"><i class="fa-regular fa-comment fa-2xl" style="color: #149f36;"></i> <b>{{count($image->comments()->get()->toArray())}}</b></a>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td><i class="fas fa-user"></i>
                    <a href="{{route('users.show', ['user'=>$image->user['id']])}}" class="text-decoration-none link-primary">{{$image->user->username}}</a></td>
                </tr>
                <tr>
                    <td><i class="bi bi-geo-alt-fill"></i>
                    {{$image->location}}</td>
                </tr>
                <tr>
                    <td><i class="far fa-calendar-alt"></i>
                    {{ $image->created_at }}</td>
                </tr>
                <tr>
                    <td>
                        Liked by:
                        <div>
                            <ul class="list-group mt-1 likes-list">
                                @forelse ($likes as $l)
                                    <li class="list-group-item"><a href="{{route('users.show', ['user'=>$l['id']])}}" class="text-decoration-none">{{$l['username']}}</a></li>
                                @empty
                                    <li class="list-group-item">No one liked yet!</li>
                                @endforelse
                            </ul>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <section style="background-color: #0D6EFD;">
        <div class="container my-5 py-5">
          <div class="row d-flex justify-content-center">
            <div class="col-md-12 col-lg-10">
              <div class="card text-dark">
                <div class="card-body p-4" style="background-color: #f8f9fa;">
                  <h4 class="mb-0">Comments</h4>
                  <p class="fw-light">Latest comments by users</p>
                </div>
                <hr class="my-0" style="height: 1px;" />

                @forelse ($comments as $comment)
                    <div class="card-body p-4">
                        <div class="d-flex flex-start">
                            <img class="rounded-circle shadow-1-strong me-3"
                                @if(str_starts_with(App\Models\User::find($comment['user_id'])['profile_picture'],"placeholder"))
                                    src={{url(App\Models\User::find($comment['user_id'])['profile_picture'])}}
                                @else
                                    src={{URL::asset("storage/".App\Models\User::find($comment['user_id'])['profile_picture'])}}
                                @endif
                            alt="avatar" width="60" height="60" />
                            <div>
                                <a href="{{route('users.show', ['user'=>$comment['user_id']])}}" class="text-decoration-none link-primary">
                                    <h6 class="fw-bold mb-1">{{App\Models\User::find($comment['user_id'])['username']}}
                                        @if ($comment['user_id'] === $image->user['id'])
                                            <span class="badge bg-primary">Creator</span>
                                        @endif
                                    </h6>
                                </a>
                                <div class="d-flex align-items-center mb-3">
                                    <p class="mb-0">
                                        {{date('Y-m-d H:i:s',strtotime($comment['created_at']))}}
                                        @auth
                                            @if (Auth::user()->isModerator())
                                                <a href="{{route('comments.delete', ["id"=>$comment['id']])}}" class="btn btn-danger btn-sm">Delete</a>
                                            @endif
                                        @endauth
                                    </p>
                                </div>
                                <p class="mb-0 bubble">
                                    {{$comment['comment']}}
                                </p>
                            </div>
                        </div>
                    </div>
                    <hr class="my-0" style="height: 1px;" />
                @empty
                <div class="col-12">
                    <div class="alert alert-warning" role="alert">
                        No comments so far
                    </div>
                </div>
                @endforelse
                <div class="card-footer p-4 border-0" style="background-color: #f8f9fa;">

                    <div class="d-flex flex-start w-100">
                        <img class="rounded-circle shadow-1-strong me-3"
                        @auth
                            @if(str_starts_with(Auth::user()['profile_picture'],"placeholder"))
                                src={{url(Auth::user()['profile_picture'])}}
                            @else
                                src={{URL::asset("storage/".Auth::user()['profile_picture'])}}
                            @endif
                        @endauth
                        @guest src={{url('placeholders/User_Profile_Blank.jpg')}} @endguest
                        alt="avatar" width="60"
                        height="60" />
                      <div class="form-outline w-100">
                        <form action="{{ route('comments.add', $image['id']) }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <label class="form-label" for="comment" id="counter">Comment - 0 / 2000</label>
                            <textarea class="form-control @error('comment') is-invalid @enderror" id="comment" name="comment" rows="4"
                            style="background: #fff;" placeholder="Enter your thoughts here (max 2000 characters)"></textarea>

                            @error('comment')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="float-end mt-2 pt-1">
                                <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-arrow-up fa-lg" style="color: #ffffff;"></i> Post comment</button>
                            </div>
                        </form>
                      </div>
                    </div>
                </div>
            </div>
          </div>
        </div>
    </section>
</div>
@endsection

@section('scripts')
    <script src="{{asset('js/likeGalleryShow.js')}}"></script>
    <script src="{{asset('js/counter.js')}}"></script>
@endsection
