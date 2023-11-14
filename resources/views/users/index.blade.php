@extends('layouts.app')
@section('title', 'Users')

@section('content')
<div class="container">
    <h1>Users - {{count($users)}}</h1>
    @auth

        @if(Session::has('fav_added'))
        <div class="alert alert-success" role="alert">
            New user followed: {{Session::get('fav_added')->username}}
        </div>
        @endif

        @if(Session::has('fav_removed'))
        <div class="alert alert-success" role="alert">
            User is no longer followed: {{Session::get('fav_removed')->username}}
        </div>
        @endif
    @endauth
    <div>
        <table class="bordered">
            <tr>
                @auth
                    <th>Follow</th>
                @endauth
                <th>Username</th>
                <th>Country</th>
                <th>Photos</th>
            </tr>
        @forelse($users as $user)
            <tr>
                 @auth <td></td>
                {{--    @if(in_array($brand['id'], array_column(Auth::user()->brands()->get()->toArray(), 'id')))
                        <td><a href="{{route('favourites.add', ['id'=>$team['id']])}}"><button>Remove</button></a></td>
                    @else
                        <td><a href="{{route('favourites.add', ['id'=>$team['id']])}}"><button>Add fav</button></a></td>
                    @endif --}}
                @endauth
                <td><a href="{{route('users.show', ['user'=>$user['id']])}}">{{$user['username']}}</a></td>
                <td>{{$user['country']}}</td>
                <td>{{App\Models\User::find($user['id'])->ownImages()->count()}}</td>
            </tr>
        @empty
            <div class="col-12">
                <div class="alert alert-warning" role="alert">
                    No users found!
                </div>
            </div>
        @endforelse
        </table>
    </div>
</div>
@endsection
