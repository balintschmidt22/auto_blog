@extends('layouts.app')
@section('title', 'Brands')

@section('content')
<div class="container">
    <h1>Brands - {{count($brands)}}</h1>
    @auth
        @if(Auth::user()->isAdmin())
            <a href="{{route('brands.create')}}">Add brand</a>
        @endif

        @if(Session::has('fav_added'))
        <div class="alert alert-success" role="alert">
            New favourite brand successfully added with name: {{Session::get('fav_added')->name}}
        </div>
        @endif

        @if(Session::has('fav_removed'))
        <div class="alert alert-success" role="alert">
            Favourite brand successfully removed with name: {{Session::get('fav_removed')->name}}
        </div>
        @endif
    @endauth
    <div>
        <table class="bordered">
            <tr>
                {{-- @auth
                    <th>Favourites</th>
                @endauth --}}
                <th>Logo</th>
                <th>Name</th>
                <th>Country</th>
            </tr>
        @forelse($brands as $brand)
            <tr>
                {{-- @auth
                    @if(in_array($brand['id'], array_column(Auth::user()->brands()->get()->toArray(), 'id')))
                        <td><a href="{{route('favourites.add', ['id'=>$team['id']])}}"><button>Remove</button></a></td>
                    @else
                        <td><a href="{{route('favourites.add', ['id'=>$team['id']])}}"><button>Add fav</button></a></td>
                    @endif
                @endauth --}}
                @if(str_starts_with($brand['image'],"https"))
                <td><img src="{{$brand['image']}}" alt="{{$brand['name']}} image"></td>
                @else
                <td><img src="{{"storage/".$brand['image']}}" alt="{{$brand['name']}} image" style="height:100px"></td>
                @endif
                <td><a href="{{route('brands.show', ['brand'=>$brand['id']])}}" class="text-decoration-none">{{$brand['name']}}</a></td>
                <td>{{$brand['country']}}</td>

            </tr>
        @empty
            <div class="col-12">
                <div class="alert alert-warning" role="alert">
                    No brands found!
                </div>
            </div>
        @endforelse
        </table>
    </div>
</div>
@endsection
