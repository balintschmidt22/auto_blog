<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Users PDF</title>
    {{-- <link rel="stylesheet" href="public/css/app.css"> --}}
</head>
<body>
    <div class="container">
        <h1>Users - {{count($users)}}</h1>
        <div class="row">
            <div class="col">
                <div>
                    <table>
                        <tr>
                            <th>Profile Picture</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Country</th>
                            <th>Photos</th>
                            <th>Last Modified</th>
                        </tr>
                    @forelse($users as $user)
                        <tr>
                            @if(str_starts_with($user['profile_picture'],"placeholder"))
                                <td>
                                    <img src={{$user['profile_picture']}} alt="{{$user['username']}} avatar" width="60" height="60">
                                </td>
                            @else
                                <td>
                                    <img src="{{"storage/".$user['profile_picture']}}" alt="{{$user['username']}} avatar" width="60" height="60">
                                </td>
                            @endif
                            <td><a href='{{route('users.show', ['user'=>$user['id']])}}'>{{$user['username']}}</a></td>
                            <td>{{$user['role']}}</td>
                            <td>{{$user['country']}}</td>
                            <td>{{App\Models\User::find($user['id'])->ownImages()->count()}}</td>
                            <td>{{$user['updated_at']}}</td>
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
        </div>
    </div>
</body>
</html>


