@extends('layouts.app')
@section('title', 'Types')

@section('content')
<div class="container">
    @auth
        @if(Auth::user()->isAdmin())
            <a href="{{route('types.create')}}" class="text-decoration-none mb-4">Add type</a>
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

    @if(str_starts_with($brand['image'],"https"))
        <h1 class="mb-4 mt-2"><img src="{{$brand['image']}}" class="img-thumbnail" alt="{{$brand['name']}} image"> {{$brand['name']}} ({{$brand['country']}}) - Types: {{'count'($types)}}</h1>
    @else
        <h1 class="mb-4 mt-2"><img src="{{URL::asset('storage/'.$brand['image'])}}" class="img-thumbnail" alt="{{$brand['name']}} image" style="height:100px"> {{$brand['name']}} ({{$brand['country']}}) - Types: {{'count'($types)}} </h1>
    @endif

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
