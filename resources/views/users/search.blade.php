@extends('layouts.app')
@section('title', 'Users')

@section('content')
<div class="container">
    <h1>Users - {{count($users)}}</h1>
    @auth
        {{-- @if(Session::has('fav_added'))
        <div class="alert alert-success" role="alert">
            New user followed: {{Session::get('fav_added')->username}}
        </div>
        @endif

        @if(Session::has('fav_removed'))
        <div class="alert alert-success" role="alert">
            User is no longer followed: {{Session::get('fav_removed')->username}}
        </div>
        @endif --}}

        @if(Session::has('user_deleted'))
        <div class="alert alert-warning" role="alert">
            User deleted: {{Session::get('user_deleted')->username}}
        </div>
        @endif
    @endauth
    <div>
        <a class="btn btn-primary mt-1 mb-3" href="{{ route('users.pdf.download') }}">Export to PDF</a>
        <a class="btn btn-primary mt-1 mb-3" href="{{ route('users.csv.download') }}">Export to CSV</a>
    </div>
    <div class="row">
        <div class="col">
            <div>
                <table class="table table-bordered" id="usersTable">
                    <tr class="table-primary">
                        {{-- @auth
                            <th>Follow</th>
                        @endauth --}}
                        <th>Profile picture</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Country</th>
                        <th>Photos</th>
                    </tr>
                @forelse($users as $user)
                    <tr>
                        {{-- @auth <td></td>
                           @if(in_array($brand['id'], array_column(Auth::user()->brands()->get()->toArray(), 'id')))
                                <td><a href="{{route('favourites.add', ['id'=>$team['id']])}}"><button>Remove</button></a></td>
                            @else
                                <td><a href="{{route('favourites.add', ['id'=>$team['id']])}}"><button>Add fav</button></a></td>
                            @endif
                        @endauth --}}
                        <td><img class="rounded-circle shadow-1-strong"
                            src={{$user['profile_picture']}} alt="avatar" width="60"
                            height="60" /></td>
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
                <input type="text" size="25" id="searchInput" placeholder="Search user..." class="float-left mt-4">
            </div>
            <div>
                <table class="table table-bordered float-left mt-4" id="foundUsers">
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

                var usernames = {}
                users.forEach(u => {
                    usernames[u['id']] = u['username']
                })

                tab.innerHTML = ""

                for (var id in usernames){
                    var row = document.createElement("tr")
                    var col = document.createElement("td")
                    var a = document.createElement("a")
                    //col.innerHTML = '<a href="' + id + '">' + usernames[id] + '</a>'
                    col.innerHTML = "<a href='users/" + id + "' class='text-decoration-none'>" + usernames[id] + "</a>"
                    //a.href = `{{route('users.show', ['user'=> 1])}}`
                    //a.innerHTML = usernames[id]
                    //a.classList.add("text-decoration-none")
                    //col.append(a)
                    row.appendChild(col)
                    tab.appendChild(row)
                }
            })
            .catch(error => {
                console.error('Error fetching data:', error);
            });
        });
    });
</script>
@endsection
