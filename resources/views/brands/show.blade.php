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
    @endauth

    <h1 class="mb-3 mt-2">
        @if(str_starts_with($brand['image'],"https"))
            <img src="{{$brand['image']}}" class="img-thumbnail" alt="{{$brand['name']}} image" style="height:110px">
        @else
            <img src="{{URL::asset('storage/'.$brand['image'])}}" class="img-thumbnail" alt="{{$brand['name']}} image" style="height:110px">
        @endif
        {{$brand['name']}} <small>({{$brand['country']}})</small>
        @auth
            @if(in_array($brand['id'], array_column(Auth::user()->followedBrands()->get()->toArray(), 'id')))
                <td><a href="{{route('follows.followBrand', ['id'=>$brand['id']])}}" class="btn btn-primary"><i class="fa-solid fa-user-minus" style="color: #ffffff;"></i></a></td>
            @else
                <td><a href="{{route('follows.followBrand', ['id'=>$brand['id']])}}" class="btn btn-primary"><i class="fa-solid fa-user-plus" style="color: #ffffff;"></i></a></td>
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
                    {{-- @auth
                        <th>Favourites</th>
                    @endauth --}}
                    <th>Type</th>
                    <th>Images</th>
                </tr>
            @endif
        @forelse($types as $type)
            <tr>
                {{-- @auth
                    @if(in_array($brand['id'], array_column(Auth::user()->brands()->get()->toArray(), 'id')))
                        <td><a href="{{route('favourites.add', ['id'=>$team['id']])}}"><button>Remove</button></a></td>
                    @else
                        <td><a href="{{route('favourites.add', ['id'=>$team['id']])}}"><button>Add fav</button></a></td>
                    @endif
                @endauth --}}
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
