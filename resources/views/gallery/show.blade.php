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
            <span>
                <a class="btn"><i class="fa-regular fa-comment fa-2xl" style="color: #149f36;"></i> <b>{{count($image->comments()->get()->toArray())}}</b></a>
            </span>

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
        </div>
    </div>

    <section style="background-color: #0D6EFD;">
        <div class="container my-5 py-5">
          <div class="row d-flex justify-content-center">
            <div class="col-md-12 col-lg-10">
              <div class="card text-dark">
                <div class="card-body p-4" style="background-color: #f8f9fa;">
                  <h4 class="mb-0">Comments</h4>
                  <p class="fw-light">Latest Comments section by users</p>
                </div>
                <hr class="my-0" style="height: 1px;" />

                @forelse ($comments as $comment)
                    <div class="card-body p-4">
                        <div class="d-flex flex-start">
                            <img class="rounded-circle shadow-1-strong me-3"
                            src={{App\Models\User::find($comment['user_id'])['profile_picture']}} alt="avatar" width="60"
                            height="60" />
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
                                        {{date('Y-m-d h:i:s',strtotime($comment['created_at']))}}
                                        <a href="#!" class="link-muted"><i class="fas fa-pencil-alt ms-2"></i></a>
                                        @auth
                                            @if (Auth::user()->isModerator())
                                                <a href="{{route('comments.delete', ["id"=>$comment['id']])}}" class="btn btn-danger btn-sm">Delete</a>
                                            @endif
                                        @endauth
                                    </p>
                                </div>
                                <p class="mb-0">
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
                        @auth src={{Auth::user()->profile_picture}} @endauth
                        @guest src="https://i.imgur.com/QqNNOcI.jpeg" @endguest
                        alt="avatar" width="60"
                        height="60" />
                      <div class="form-outline w-100">
                        <form action="{{ route('comments.add', $image['id']) }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <label class="form-label" for="comment">Comment:</label>
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
