@extends('layouts.app')
@section('title', 'Users')

@section('content')
<div class="container bg-light p-4">
    <h1>Users - {{count($users)}}</h1>
    @if(Session::has('user_deleted'))
        <div class="alert alert-warning" role="alert">
            User deleted: {{Session::get('user_deleted')->username}}
        </div>
    @endif
    <div>
        <a class="btn btn-primary mt-1 mb-2" href="{{ route('users.pdf.download') }}">Export to PDF</a>
        <a class="btn btn-primary mt-1 mb-2 m-1" href="{{ route('users.csv.download') }}">Export to CSV</a>
        <input type="text" size="25" id="searchInput" placeholder="Search user..." class="float-left m-2">
    </div>
    <div class="row">
        <div class="col">
            <div>
                <table class="table table-bordered table-striped" id="usersTable">
                    <tr class="table-primary">
                        <th>Profile picture</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Country</th>
                        <th>Photos</th>
                        <th>Follow</th>
                        @auth
                            <th>Message</th>
                        @endauth
                    </tr>
                @forelse($users as $user)
                    <tr>
                        @if(str_starts_with($user['profile_picture'],"placeholder"))
                            <td>
                                <img class="rounded-circle shadow-1-strong" src={{$user['profile_picture']}} alt="{{$user['username']}} avatar" width="60" height="60">
                            </td>
                        @else
                            <td>
                                <img class="rounded-circle shadow-1-strong" src="{{"storage/".$user['profile_picture']}}" alt="{{$user['username']}} avatar" width="60" height="60">
                            </td>
                        @endif
                        <td><a href='{{route('users.show', ['user'=>$user['id']])}}' class="text-decoration-none">{{$user['username']}}</a></td>
                        <td>@if ($user['role'] === "adm")
                            admin
                        @elseif ($user['role'] === "mod")
                            moderator
                        @else
                            user
                        @endif</td>
                        <td>{{$user['country']}}</td>
                        <td>{{App\Models\User::find($user['id'])->ownImages()->count()}}</td>
                        @guest
                            <td><a href="{{route('login')}}" class="btn btn-primary">
                            <i class="fa-solid fa-user-plus" style="color: #ffffff;"></i>
                            </a></td>
                        @endguest
                        @auth
                            @if (Auth::id() == $user['id'])
                                <td>-</td>
                                <td>-</td>
                            @else
                                @if (Auth::user()->email_verified_at === null)
                                    <td><a href="{{route('verification.notice')}}" class="btn btn-primary">
                                    <i class="fa-solid fa-user-plus" style="color: #ffffff;"></i>
                                    </a></td>
                                @else
                                    <td><a class="btn btn-primary followButton" id="{{$user['id']}}">
                                        @if(in_array($user['id'], array_column(Auth::user()->follows()->get()->toArray(), 'id')))
                                            <i class="fa-solid fa-user-minus" style="color: #ffffff;" id="unfollowed"></i>
                                        @else
                                            <i class="fa-solid fa-user-plus" style="color: #ffffff;" id="followed"></i>
                                        @endif
                                    </a></td>
                                @endif
                                <td><a href="{{route('users.message', ['id'=>$user['id']])}}" class="btn btn-primary"><i class="fa-solid fa-message" style="color: #ffffff;"></i></a></td>
                            @endif
                        @endauth
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
        <div class="col-lg-4">
            <div>
                <table class="table table-bordered float-left" id="foundUsers">
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="{{asset('js/userSearch.js')}}"></script>
    <script src="{{asset('js/follow.js')}}"></script>
@endsection
