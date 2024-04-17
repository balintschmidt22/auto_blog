@extends('layouts.app')
@section('title', 'Types')

@section('content')
<div class="container">
    @auth
        @if(Session::has('followed'))
            <div class="alert alert-success" role="alert">
                New brand followed: {{Session::get('followed')->name}}
            </div>
        @endif

        @if(Session::has('unfollowed'))
            <div class="alert alert-warning" role="alert">
                Brand is no longer followed: {{Session::get('unfollowed')->name}}
            </div>
        @endif

        @if(Session::has('type_deleted'))
            <div class="alert alert-warning" role="alert">
                Type deleted: {{Session::get('type_deleted')->type}}
            </div>
        @endif

        @if(Session::has('brand_edited'))
            <div class="alert alert-success" role="alert">
                Brand edited: {{Session::get('brand_edited')->name}} at {{Session::get('brand_edited')->updated_at}}
            </div>
        @endif
    @endauth

    <h1 class="mb-3 mt-2">
        @if(str_starts_with($brand['image'],"placeholder"))
            <img src="{{url($brand['image'])}}" class="img-thumbnail" alt="{{$brand['name']}} image" style="height:110px">
        @else
            <img src="{{URL::asset('storage/'.$brand['image'])}}" class="img-thumbnail" alt="{{$brand['name']}} image" style="height:110px">
        @endif
        {{$brand['name']}} <small>({{$brand['country']}})</small>
        @guest
            <td><a href="{{route('login')}}" class="btn btn-primary"><i class="fa-solid fa-user-plus" style="color: #ffffff;"></i></a></td>
        @endguest
        @auth
            @if (Auth::user()->email_verified_at === null)
                <td><a href="{{route('verification.notice')}}" class="btn btn-primary"><i class="fa-solid fa-user-plus" style="color: #ffffff;"></i></a></td>
            @else
                <td><a class="btn btn-primary followButton" id="{{$brand['id']}}">
                    @if(in_array($brand['id'], array_column(Auth::user()->followedBrands()->get()->toArray(), 'id')))
                        <i class="fa-solid fa-user-minus" style="color: #ffffff;" id="unfollowed"></i>
                    @else
                        <i class="fa-solid fa-user-plus" style="color: #ffffff;" id="followed"></i>
                    @endif
                </a></td>
            @endif
        @endauth
         - Types: {{'count'($types)}}
    </h1>
    <div>
        <h5 class="mb-3">| Follows: {{$followedBy}} | Likes: {{$likedBy}}
            @auth
                @if(Auth::user()->isModerator())
                    | <a href="{{route('types.create')}}" class="text-decoration-none mb-4">Add type</a>
                    | <a href="{{route('brands.edit', [$brand])}}" class="text-decoration-none mb-4 text-warning">Modify brand</a>
                @endif
                @if(Auth::user()->isAdmin())
                    |
                    <a href="{{route('brands.delete', ['id'=>$brand['id']])}}" class="text-decoration-none mb-4 text-danger">Delete brand</a>
                @endif
                |
            @endauth
        </h5>
    </div>

    <div>
        <table class="table table-bordered">
            @if (count($types) != 0)
                <tr class="table-primary">
                    <th>Type</th>
                    <th>Images</th>
                </tr>
            @endif
        @forelse($types as $type)
            <tr>
                <td><a href="{{route('types.show', ['type'=>$type['id']])}}" class="text-decoration-none">{{$type['type']}}</a></td>
                <td>{{App\Models\Type::find($type['id'])->images()->count()}}</td>
            </tr>
        @empty
            <div class="col-12">
                <div class="alert alert-warning" role="alert">
                    No types found!
                </div>
            </div>
        @endforelse
        </table>
    </div>
</div>
@endsection

@section('scripts')
    <script src="{{asset('js/followBrand.js')}}"></script>
@endsection
