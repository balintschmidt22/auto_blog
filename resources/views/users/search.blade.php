@extends('layouts.app')
@section('title', 'Users')

@section('content')
<div class="container">
    <h1>Users - {{count($users)}}</h1>
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

        @if(Session::has('user_deleted'))
            <div class="alert alert-warning" role="alert">
                User deleted: {{Session::get('user_deleted')->username}}
            </div>
        @endif
    @endauth
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
                        @auth
                            <th>Follow</th>
                            <th>Message</th>
                        @endauth
                    </tr>
                @forelse($users as $user)
                    <tr>
                        @if(str_starts_with($user['profile_picture'],"https"))
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
                        @auth
                            @if (Auth::id() == $user['id'])
                                <td>-</td>
                                <td>-</td>
                            @else
                                @if(in_array($user['id'], array_column(Auth::user()->follows()->get()->toArray(), 'id')))
                                    <td><a href="{{route('follows.followUser', ['id'=>$user['id']])}}" class="btn btn-primary"><i class="fa-solid fa-user-minus" style="color: #ffffff;"></i></a></td>
                                @else
                                    <td><a href="{{route('follows.followUser', ['id'=>$user['id']])}}" class="btn btn-primary"><i class="fa-solid fa-user-plus" style="color: #ffffff;"></i></a></td>
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
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('searchInput');

        searchInput.addEventListener('input', function () {
            const query = searchInput.value;

            const tab = document.getElementById("foundUsers")
            tab.innerHTML = ""

            axios.post('/users/search', {
                params: {
                    search: query,
                },
            })
            .then((response) => {
                // Update the data list with the filtered data
                var users = Object.values(response.data);

                tab.innerHTML = ""

                users.forEach(u => {
                    var row = document.createElement("tr")
                    var col = document.createElement("td")
                    var a = document.createElement("a")

                    col.innerHTML = "<a href='users/" + u['id'] + "' class='text-decoration-none'>" + u['username'] + "</a>"
                    row.appendChild(col)
                    tab.appendChild(row)
                })
            })
            .catch(error => {
                console.error('Error fetching data:', error);
            });
        });
    });
</script>
@endsection
